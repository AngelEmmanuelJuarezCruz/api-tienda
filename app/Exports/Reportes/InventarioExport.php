<?php

declare(strict_types=1);

namespace App\Exports\Reportes;

use App\Models\Producto;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InventarioExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection(): Collection
    {
        return Producto::with('categoria')
            ->orderBy('nombre')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Producto',
            'Categoria',
            'SKU',
            'Precio compra',
            'Precio venta',
            'Stock actual',
            'Stock minimo',
        ];
    }

    public function map($producto): array
    {
        return [
            $producto->nombre,
            $producto->categoria?->nombre ?? 'Sin categoria',
            $producto->sku ?? $producto->codigo_barras,
            number_format((float) $producto->precio_compra, 2, '.', ''),
            number_format((float) $producto->precio_venta, 2, '.', ''),
            $producto->stock_actual,
            $producto->stock_minimo,
        ];
    }
}
