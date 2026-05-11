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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportesController extends Controller
{
    /**
     * Mostrar el panel de reportes.
     */
    public function index(Request $request)
    {
        $type = $request->string('tipo', 'ventas')->toString();
        $range = $this->resolveRange($request);
        $data = $this->buildReportData($type, $range['desde'], $range['hasta']);

        return view('admin.reportes.index', [
            'tipo' => $type,
            'desde' => $range['desde_input'],
            'hasta' => $range['hasta_input'],
            'resumen' => $data['resumen'],
            'rows' => $data['rows'],
        ]);
    }

    /**
     * Exportar reportes en PDF, Excel o ambos.
     */
    public function export(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|string|in:ventas,entradas,salidas,inventario',
            'formato' => 'required|string|in:pdf,excel,ambos',
            'desde' => 'required|date',
            'hasta' => 'required|date|after_or_equal:desde',
        ]);

        $range = $this->resolveRange($request);
        $lockKey = $this->buildExportLockKey($validated['tipo'], $validated['formato'], $range['desde_input'], $range['hasta_input']);

        if (! Cache::add($lockKey, true, now()->addSeconds(20))) {
            return back()->with('warning', 'Ya hay una exportacion en proceso. Espera unos segundos e intenta de nuevo.');
        }

        try {
            if ($validated['formato'] === 'pdf') {
                return $this->exportPdf($validated['tipo'], $range['desde'], $range['hasta'], $range['desde_input'], $range['hasta_input']);
            }

            if ($validated['formato'] === 'excel') {
                return $this->exportExcel($validated['tipo'], $range['desde'], $range['hasta'], $range['desde_input'], $range['hasta_input']);
            }

            return $this->exportBoth($validated['tipo'], $range['desde'], $range['hasta'], $range['desde_input'], $range['hasta_input']);
        } finally {
            Cache::forget($lockKey);
        }
    }

    private function resolveRange(Request $request): array
    {
        $desdeInput = $request->input('desde');
        $hastaInput = $request->input('hasta');

        $desde = $desdeInput ? Carbon::parse($desdeInput)->startOfDay() : now()->subDays(30)->startOfDay();
        $hasta = $hastaInput ? Carbon::parse($hastaInput)->endOfDay() : now()->endOfDay();

        return [
            'desde' => $desde,
            'hasta' => $hasta,
            'desde_input' => $desde->toDateString(),
            'hasta_input' => $hasta->toDateString(),
        ];
    }

    private function buildReportData(string $type, Carbon $desde, Carbon $hasta): array
    {
        if ($type === 'entradas') {
            $rows = EntradaInventario::with(['producto', 'proveedor', 'usuario'])
                ->whereBetween('fecha', [$desde, $hasta])
                ->orderByDesc('fecha')
                ->get();

            $totalCantidad = $rows->sum('cantidad');
            $totalCosto = $rows->sum(fn($row) => $row->cantidad * $row->costo_unitario);

            return [
                'resumen' => [
                    'total_registros' => $rows->count(),
                    'total_cantidad' => $totalCantidad,
                    'total_costo' => $totalCosto,
                ],
                'rows' => $rows,
            ];
        }

        if ($type === 'salidas') {
            $rows = SalidaInventario::with(['producto', 'usuario'])
                ->whereBetween('fecha', [$desde, $hasta])
                ->orderByDesc('fecha')
                ->get();

            $totalCantidad = $rows->sum('cantidad');
            $porMotivo = $rows->groupBy('motivo')->map->count();

            return [
                'resumen' => [
                    'total_registros' => $rows->count(),
                    'total_cantidad' => $totalCantidad,
                    'motivos' => $porMotivo,
                ],
                'rows' => $rows,
            ];
        }

        if ($type === 'inventario') {
            $rows = Producto::with('categoria')
                ->orderBy('nombre')
                ->get();

            $totalStock = $rows->sum('stock_actual');
            $totalValor = $rows->sum(fn($row) => $row->stock_actual * $row->precio_venta);
            $bajoStock = $rows->filter(fn($row) => $row->stock_actual <= $row->stock_minimo)->count();

            return [
                'resumen' => [
                    'total_productos' => $rows->count(),
                    'total_stock' => $totalStock,
                    'total_valor' => $totalValor,
                    'bajo_stock' => $bajoStock,
                ],
                'rows' => $rows,
            ];
        }

        $rows = Venta::with(['usuario', 'detallesVenta.producto'])
            ->whereBetween('fecha', [$desde, $hasta])
            ->orderByDesc('fecha')
            ->get();

        $totalVentas = $rows->count();
        $totalMonto = $rows->sum('total');
        $totalItems = $rows->flatMap->detallesVenta->sum('cantidad');
        $ticketPromedio = $totalVentas > 0 ? $totalMonto / $totalVentas : 0;

        $topProductos = DetalleVenta::whereHas('venta', function ($query) use ($desde, $hasta) {
            $query->whereBetween('fecha', [$desde, $hasta]);
        })
            ->with('producto')
            ->get()
            ->groupBy('producto_id')
            ->map(function ($group) {
                $producto = $group->first()->producto;
                return [
                    'producto' => $producto,
                    'cantidad' => $group->sum('cantidad'),
                    'total' => $group->sum('subtotal'),
                ];
            })
            ->sortByDesc('cantidad')
            ->take(5);

        return [
            'resumen' => [
                'total_ventas' => $totalVentas,
                'total_monto' => $totalMonto,
                'total_items' => $totalItems,
                'ticket_promedio' => $ticketPromedio,
                'top_productos' => $topProductos,
            ],
            'rows' => $rows,
        ];
    }

    private function exportExcel(string $type, Carbon $desde, Carbon $hasta, string $desdeLabel, string $hastaLabel)
    {
        $fileName = $this->buildFileName($type, $desdeLabel, $hastaLabel, 'xlsx');

        return match ($type) {
            'entradas' => Excel::download(new EntradasExport($desde, $hasta), $fileName),
            'salidas' => Excel::download(new SalidasExport($desde, $hasta), $fileName),
            'inventario' => Excel::download(new InventarioExport(), $fileName),
            default => Excel::download(new VentasExport($desde, $hasta), $fileName),
        };
    }

    private function exportPdf(string $type, Carbon $desde, Carbon $hasta, string $desdeLabel, string $hastaLabel)
    {
        $data = $this->buildReportData($type, $desde, $hasta);
        $view = "admin.reportes.pdf.{$type}";
        $fileName = $this->buildFileName($type, $desdeLabel, $hastaLabel, 'pdf');

        $pdf = Pdf::loadView($view, [
            'desde' => $desdeLabel,
            'hasta' => $hastaLabel,
            'resumen' => $data['resumen'],
            'rows' => $data['rows'],
        ])->setPaper('a4', 'landscape');

        return $pdf->download($fileName);
    }

    private function exportBoth(string $type, Carbon $desde, Carbon $hasta, string $desdeLabel, string $hastaLabel)
    {
        if (! class_exists('ZipArchive')) {
            return back()->withErrors(['formato' => 'ZipArchive no esta disponible en PHP.']);
        }

        $baseName = $this->buildFileName($type, $desdeLabel, $hastaLabel, 'zip');
        $excelFile = $this->buildFileName($type, $desdeLabel, $hastaLabel, 'xlsx');
        $pdfFile = $this->buildFileName($type, $desdeLabel, $hastaLabel, 'pdf');

        $tmpDir = storage_path('app/tmp');
        if (! is_dir($tmpDir)) {
            mkdir($tmpDir, 0755, true);
        }

        $zipPath = $tmpDir . '/' . $baseName;
        $excelPath = $tmpDir . '/' . $excelFile;
        $pdfPath = $tmpDir . '/' . $pdfFile;

        $data = $this->buildReportData($type, $desde, $hasta);
        $view = "admin.reportes.pdf.{$type}";

        $pdf = Pdf::loadView($view, [
            'desde' => $desdeLabel,
            'hasta' => $hastaLabel,
            'resumen' => $data['resumen'],
            'rows' => $data['rows'],
        ])->setPaper('a4', 'landscape');
        $pdf->save($pdfPath);

        $exporter = match ($type) {
            'entradas' => new EntradasExport($desde, $hasta),
            'salidas' => new SalidasExport($desde, $hasta),
            'inventario' => new InventarioExport(),
            default => new VentasExport($desde, $hasta),
        };

        Excel::store($exporter, 'tmp/' . $excelFile, 'local');

        if (! file_exists($pdfPath) || ! file_exists($excelPath)) {
            return back()->withErrors(['formato' => 'No se pudieron generar los archivos temporales del reporte.']);
        }

        $zip = new \ZipArchive();
        $zipStatus = $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        if ($zipStatus !== true) {
            return back()->withErrors(['formato' => 'No se pudo crear el archivo ZIP del reporte.']);
        }

        $zip->addFile($pdfPath, $pdfFile);
        $zip->addFile($excelPath, $excelFile);
        $zip->close();

        if (file_exists($pdfPath)) {
            unlink($pdfPath);
        }
        if (file_exists($excelPath)) {
            unlink($excelPath);
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    private function buildFileName(string $type, string $desde, string $hasta, string $ext): string
    {
        $slug = Str::slug("{$type}-{$desde}-{$hasta}");
        return "reporte-{$slug}.{$ext}";
    }

    private function buildExportLockKey(string $type, string $format, string $desde, string $hasta): string
    {
        return "reportes:{$type}:{$format}:{$desde}:{$hasta}";
    }
}
