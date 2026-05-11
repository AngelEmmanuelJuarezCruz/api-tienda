<?php

declare(strict_types=1);

namespace App\Exports\Reportes;

use App\Models\Venta;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Carbon;

class VentasExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(
        private readonly Carbon $desde,
        private readonly Carbon $hasta
    ) {}

    public function collection(): Collection
    {
        return Venta::with('usuario')
            ->whereBetween('fecha', [$this->desde, $this->hasta])
            ->orderByDesc('fecha')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Folio',
            'Fecha',
            'Usuario',
            'Total',
        ];
    }

    public function map($venta): array
    {
        return [
            $venta->folio,
            $venta->fecha?->format('Y-m-d H:i') ?? '',
            $venta->usuario?->name ?? 'Sin usuario',
            number_format((float) $venta->total, 2, '.', ''),
        ];
    }
}
