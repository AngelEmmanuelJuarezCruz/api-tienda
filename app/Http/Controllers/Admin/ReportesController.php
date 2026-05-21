<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\Reportes\EntradasExport;
use App\Exports\Reportes\InventarioExport;
use App\Exports\Reportes\SalidasExport;
use App\Exports\Reportes\VentasExport;
use App\Models\DetalleVenta;
use App\Models\EntradaInventario;
use App\Models\Producto;
use App\Models\SalidaInventario;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use ZipArchive;

class ReportesController extends Controller
{
    /**
     * Mostrar el panel de reportes.
     */
    public function index(Request $request)
    {
        $ventas = \App\Models\Venta::with('usuario')
            ->orderBy('fecha', 'desc')
            ->limit(100)
            ->get();

        $cortes = \App\Models\TurnoCaja::with('usuario')
            ->orderBy('id', 'desc')
            ->limit(50)
            ->get();

        return view('admin.reportes.index', compact('ventas', 'cortes'));
    }

    /**
     * Descargar reportes en PDF, Excel o paquete ZIP.
     */
    public function export(Request $request)
    {
        $data = $request->validate([
            'tipo' => ['required', 'in:ventas,entradas,salidas,inventario'],
            'formato' => ['required', 'in:pdf,excel,zip'],
            'desde' => ['nullable', 'date'],
            'hasta' => ['nullable', 'date', 'after_or_equal:desde'],
        ]);

        $desde = Carbon::parse($data['desde'] ?? now()->subMonth()->toDateString())->startOfDay();
        $hasta = Carbon::parse($data['hasta'] ?? now()->toDateString())->endOfDay();
        $tipo = $data['tipo'];
        $formato = $data['formato'];

        if ($formato === 'pdf') {
            return $this->downloadPdf($tipo, $desde, $hasta);
        }

        if ($formato === 'excel') {
            return Excel::download(
                $this->makeExcelExport($tipo, $desde, $hasta),
                $this->fileName($tipo, $desde, $hasta, 'xlsx')
            );
        }

        return $this->downloadZip($desde, $hasta);
    }

    /**
     * Ajustar un corte de caja cerrado.
     */
    public function ajustarCorte(Request $request, $id)
    {
        $request->validate([
            'gastos' => 'required|numeric|min:0',
            'efectivo_real' => 'required|numeric|min:0',
        ]);

        $turno = \App\Models\TurnoCaja::findOrFail($id);

        if ($turno->estado !== 'Cerrado') {
            return back()->withErrors(['error' => 'Solo se pueden ajustar turnos que ya estén cerrados.']);
        }

        // LÓGICA DE RECALCULO CRÍTICA
        $turno->gastos = $request->gastos;
        $turno->efectivo_real = $request->efectivo_real;
        
        $turno->efectivo_esperado = $turno->fondo_inicial + $turno->ingresos_ventas - $turno->gastos;
        $turno->diferencia = $turno->efectivo_real - $turno->efectivo_esperado;
        
        $turno->save();

        return back()->with('success', 'El corte de caja ha sido ajustado y recalculado exitosamente.');
    }

    private function downloadPdf(string $tipo, Carbon $desde, Carbon $hasta)
    {
        $payload = $this->reportPayload($tipo, $desde, $hasta);

        return Pdf::loadView("admin.reportes.pdf.{$tipo}", $payload)
            ->setPaper('letter', 'landscape')
            ->download($this->fileName($tipo, $desde, $hasta, 'pdf'));
    }

    private function downloadZip(Carbon $desde, Carbon $hasta)
    {
        $baseName = 'paquete-reportes-' . $desde->format('Ymd') . '-' . $hasta->format('Ymd');
        $relativeDirectory = "reportes/{$baseName}-" . uniqid();
        $directory = Storage::disk('local')->path($relativeDirectory);
        File::ensureDirectoryExists($directory);

        $zipPath = "{$directory}/{$baseName}.zip";
        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            abort(500, 'No se pudo crear el archivo ZIP.');
        }

        foreach (['ventas', 'entradas', 'salidas', 'inventario'] as $tipo) {
            $pdfName = $this->fileName($tipo, $desde, $hasta, 'pdf');
            $pdfPath = "{$directory}/{$pdfName}";
            File::put($pdfPath, Pdf::loadView("admin.reportes.pdf.{$tipo}", $this->reportPayload($tipo, $desde, $hasta))
                ->setPaper('letter', 'landscape')
                ->output());
            $zip->addFile($pdfPath, "pdf/{$pdfName}");

            $excelName = $this->fileName($tipo, $desde, $hasta, 'xlsx');
            $excelRelativePath = "{$relativeDirectory}/{$excelName}";
            Excel::store($this->makeExcelExport($tipo, $desde, $hasta), $excelRelativePath, 'local');
            $excelPath = Storage::disk('local')->path($excelRelativePath);
            $zip->addFile($excelPath, "excel/{$excelName}");
        }

        $zip->close();

        return response()->download($zipPath, "{$baseName}.zip")->deleteFileAfterSend(true);
    }

    private function makeExcelExport(string $tipo, Carbon $desde, Carbon $hasta): object
    {
        return match ($tipo) {
            'ventas' => new VentasExport($desde, $hasta),
            'entradas' => new EntradasExport($desde, $hasta),
            'salidas' => new SalidasExport($desde, $hasta),
            'inventario' => new InventarioExport(),
        };
    }

    private function reportPayload(string $tipo, Carbon $desde, Carbon $hasta): array
    {
        $rows = match ($tipo) {
            'ventas' => Venta::with('usuario')->whereBetween('fecha', [$desde, $hasta])->orderByDesc('fecha')->get(),
            'entradas' => EntradaInventario::with(['producto', 'proveedor', 'usuario'])->whereBetween('fecha', [$desde, $hasta])->orderByDesc('fecha')->get(),
            'salidas' => SalidaInventario::with(['producto', 'usuario'])->whereBetween('fecha', [$desde, $hasta])->orderByDesc('fecha')->get(),
            'inventario' => Producto::with('categoria')->orderBy('nombre')->get(),
        };

        return [
            'rows' => $rows,
            'resumen' => $this->summary($tipo, $rows),
            'desde' => $desde->format('Y-m-d'),
            'hasta' => $hasta->format('Y-m-d'),
        ];
    }

    private function summary(string $tipo, $rows): array
    {
        return match ($tipo) {
            'ventas' => [
                'total_ventas' => $rows->count(),
                'total_monto' => $rows->sum('total'),
                'total_items' => DetalleVenta::whereIn('venta_id', $rows->pluck('id'))->sum('cantidad'),
                'ticket_promedio' => $rows->count() ? $rows->avg('total') : 0,
            ],
            'entradas' => [
                'total_registros' => $rows->count(),
                'total_cantidad' => $rows->sum('cantidad'),
                'total_costo' => $rows->sum(fn ($row) => $row->cantidad * $row->costo_unitario),
            ],
            'salidas' => [
                'total_registros' => $rows->count(),
                'total_cantidad' => $rows->sum('cantidad'),
            ],
            'inventario' => [
                'total_productos' => $rows->count(),
                'total_stock' => $rows->sum('stock_actual'),
                'total_valor' => $rows->sum(fn ($row) => $row->stock_actual * $row->precio_compra),
                'bajo_stock' => $rows->filter(fn ($row) => $row->stock_actual <= $row->stock_minimo)->count(),
            ],
        };
    }

    private function fileName(string $tipo, Carbon $desde, Carbon $hasta, string $extension): string
    {
        return "reporte-{$tipo}-{$desde->format('Ymd')}-{$hasta->format('Ymd')}.{$extension}";
    }
}
