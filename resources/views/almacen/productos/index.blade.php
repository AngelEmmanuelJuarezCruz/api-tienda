@extends('layouts.app')

@section('titulo', 'Productos')

@section('content')
<div class="bg-white rounded-xl border shadow-sm overflow-hidden">
    <div class="p-5 border-b">
        <h3 class="text-lg font-semibold text-gray-800">Catálogo de productos</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="text-left px-5 py-3">SKU</th>
                    <th class="text-left px-5 py-3">Producto</th>
                    <th class="text-left px-5 py-3">Categoría</th>
                    <th class="text-left px-5 py-3">Proveedor</th>
                    <th class="text-right px-5 py-3">Stock</th>
                    <th class="text-right px-5 py-3">Precio</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach ($productos as $producto)
                    <tr>
                        <td class="px-5 py-3">{{ $producto->sku }}</td>
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $producto->nombre }}</td>
                        <td class="px-5 py-3">{{ $producto->categoria?->nombre ?? 'Sin categoría' }}</td>
                        <td class="px-5 py-3">{{ $producto->proveedor?->nombre ?? 'Sin proveedor' }}</td>
                        <td class="px-5 py-3 text-right {{ $producto->stock_actual <= $producto->stock_minimo ? 'text-red-600 font-semibold' : 'text-gray-700' }}">{{ $producto->stock_actual }}</td>
                        <td class="px-5 py-3 text-right">$ {{ number_format((float) $producto->precio_venta, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
