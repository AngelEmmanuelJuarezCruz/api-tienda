<?php

declare(strict_types=1);

namespace App\Exports\Reportes;

use App\Models\SalidaInventario;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SalidasExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(
        private readonly Carbon $desde,
        private readonly Carbon $hasta
    ) {}

    public function collection(): Collection
    {
        return SalidaInventario::with(['producto', 'usuario'])
            ->whereBetween('fecha', [$this->desde, $this->hasta])
            ->orderByDesc('fecha')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Producto',
            'Usuario',
            'Cantidad',
            'Motivo',
            'Justificacion',
        ];
    }

    public function map($salida): array
    {
        return [
            $salida->fecha?->format('Y-m-d H:i') ?? '',
            $salida->producto?->nombre ?? 'Sin producto',
            $salida->usuario?->name ?? 'Sin usuario',
            $salida->cantidad,
            $salida->motivo,
            $salida->justificacion ?? '',
        ];
    }
}
