@extends('layouts.app')

@section('titulo', 'Alertas de Inventario')

@section('content')
<div class="grid gap-6">
    <div class="bg-white rounded-xl border p-5 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Alertas de inventario</h1>
                <p class="text-sm text-gray-500 mt-1">Controla los insumos críticos: stock bajo o caducidad próxima en los próximos 7 días.</p>
            </div>
            <div class="inline-flex items-center gap-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-600 text-white text-xs font-semibold">Stock bajo</span>
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-500 text-white text-xs font-semibold">Caducidad próxima</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
        <div class="p-5 border-b flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800">Productos en alerta</h2>
            <a href="{{ route('almacen.productos') }}" class="text-sm text-indigo-600 hover:underline">Ver inventario completo</a>
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
                        <th class="text-center px-5 py-3">Alerta</th>
                        <th class="text-right px-5 py-3">Caducidad</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($productos as $producto)
                        <tr class="{{ $producto->stock_actual <= $producto->stock_minimo ? 'bg-red-50' : ($producto->tiene_caducidad && $producto->fecha_caducidad && $producto->fecha_caducidad <= now()->addDays(7) ? 'bg-yellow-50' : '') }}">
                            <td class="px-5 py-3">{{ $producto->sku }}</td>
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $producto->nombre }}</td>
                            <td class="px-5 py-3">{{ $producto->categoria?->nombre ?? 'Sin categoría' }}</td>
                            <td class="px-5 py-3">{{ $producto->proveedor?->nombre ?? 'Sin proveedor' }}</td>
                            <td class="px-5 py-3 text-right font-semibold {{ $producto->stock_actual <= $producto->stock_minimo ? 'text-red-600' : 'text-gray-700' }}">{{ $producto->stock_actual }}</td>
                            <td class="px-5 py-3 text-center">
                                @if($producto->stock_actual <= $producto->stock_minimo)
                                    <span class="inline-flex px-2 py-1 rounded-full bg-red-600 text-white text-xs font-semibold">STOCK BAJO</span>
                                @elseif($producto->tiene_caducidad && $producto->fecha_caducidad && $producto->fecha_caducidad <= now()->addDays(7))
                                    <span class="inline-flex px-2 py-1 rounded-full bg-amber-500 text-white text-xs font-semibold">CADUCA PRONTO</span>
                                @else
                                    <span class="text-gray-500 text-xs">OK</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-right text-gray-700">{{ $producto->fecha_caducidad ? date('d/m/Y', strtotime($producto->fecha_caducidad)) : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center text-gray-500">No hay productos en alerta actualmente.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
