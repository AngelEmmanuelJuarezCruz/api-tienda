<?php

declare(strict_types=1);

namespace App\Exports\Reportes;

use App\Models\EntradaInventario;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EntradasExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(
        private readonly Carbon $desde,
        private readonly Carbon $hasta
    ) {}

    public function collection(): Collection
    {
        return EntradaInventario::with(['producto', 'proveedor', 'usuario'])
            ->whereBetween('fecha', [$this->desde, $this->hasta])
            ->orderByDesc('fecha')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Producto',
            'Proveedor',
            'Usuario',
            'Cantidad',
            'Costo unitario',
            'Total',
            'Notas',
        ];
    }

    public function map($entrada): array
    {
        $total = $entrada->cantidad * $entrada->costo_unitario;

        return [
            $entrada->fecha?->format('Y-m-d H:i') ?? '',
            $entrada->producto?->nombre ?? 'Sin producto',
            $entrada->proveedor?->nombre ?? 'Sin proveedor',
            $entrada->usuario?->name ?? 'Sin usuario',
            $entrada->cantidad,
            number_format((float) $entrada->costo_unitario, 2, '.', ''),
            number_format((float) $total, 2, '.', ''),
            $entrada->notas ?? '',
        ];
    }
}
