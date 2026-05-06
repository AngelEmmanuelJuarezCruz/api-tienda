@extends('layouts.app')

@section('titulo', 'Panel de Almacén')

@section('content')
<div class="grid gap-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <p class="text-sm text-gray-500">Productos</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['productos'] }}</p>
        </div>
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <p class="text-sm text-gray-500">Stock bajo</p>
            <p class="mt-2 text-3xl font-bold text-red-600">{{ $stats['stock_bajo'] }}</p>
        </div>
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <p class="text-sm text-gray-500">Próximos a caducar</p>
            <p class="mt-2 text-3xl font-bold text-amber-600">{{ $stats['proximos_caducar'] }}</p>
        </div>
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <p class="text-sm text-gray-500">Categorías</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['categorias'] }}</p>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-800">Alerta rápida</h3>
            <p class="text-sm text-gray-500 mt-2">Visualiza rápidamente productos con baja existencia o caducidad cercana.</p>
            <div class="mt-4">
                <a href="{{ route('almacen.alertas') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-semibold hover:bg-red-700">Ver alertas</a>
            </div>
        </div>
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-800">Buscar en inventario</h3>
            <p class="text-sm text-gray-500 mt-2">Filtra por categoría, proveedor o caducidad usando el inventario completo.</p>
            <div class="mt-4">
                <a href="{{ route('almacen.productos') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">Abrir tabla</a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
        <div class="p-5 border-b flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Inventario crítico</h3>
            <a href="{{ route('almacen.productos') }}" class="text-sm text-indigo-600 hover:underline">Ver todos</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="text-left px-5 py-3">SKU</th>
                        <th class="text-left px-5 py-3">Producto</th>
                        <th class="text-left px-5 py-3">Categoría</th>
                        <th class="text-right px-5 py-3">Stock</th>
                        <th class="text-right px-5 py-3">Mínimo</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach ($productos as $producto)
                        <tr>
                            <td class="px-5 py-3">{{ $producto->sku }}</td>
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $producto->nombre }}</td>
                            <td class="px-5 py-3">{{ $producto->categoria?->nombre ?? 'Sin categoría' }}</td>
                            <td class="px-5 py-3 text-right {{ $producto->stock_actual <= $producto->stock_minimo ? 'text-red-600 font-semibold' : 'text-gray-700' }}">{{ $producto->stock_actual }}</td>
                            <td class="px-5 py-3 text-right text-gray-700">{{ $producto->stock_minimo }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
