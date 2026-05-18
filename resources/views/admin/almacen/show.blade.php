@extends('layouts.app')

@section('title', 'Historial de Movimientos - ' . $producto->nombre)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('almacen.productos') }}" class="p-2 bg-white text-gray-500 hover:text-blue-600 rounded-lg shadow-sm border border-gray-100 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Historial de Movimientos</h2>
                <p class="text-sm text-gray-500 mt-1">Bitácora de entradas, salidas y mermas del producto.</p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-sm font-semibold text-gray-500">Stock Actual</p>
            <p class="text-2xl font-bold text-[#1E3A8A]">{{ $producto->stock_actual }}</p>
        </div>
    </div>

    <!-- Resumen del Producto -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 mb-8 flex flex-col md:flex-row gap-8">
        <div class="flex-1">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Código (SKU)</p>
            <p class="text-lg font-bold text-gray-800">{{ $producto->sku ?? $producto->codigo_barras ?? 'Sin código' }}</p>
        </div>
        <div class="flex-1">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Producto</p>
            <p class="text-lg font-bold text-gray-800">{{ $producto->nombre }}</p>
        </div>
        <div class="flex-1">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Categoría</p>
            <span class="inline-flex items-center rounded-md bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                {{ $producto->categoria?->nombre ?? 'Sin categoría' }}
            </span>
        </div>
        <div class="flex-1">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Precio Venta</p>
            <p class="text-lg font-bold text-emerald-600">${{ number_format($producto->precio_venta, 2) }}</p>
        </div>
    </div>

    <!-- Tabla de Bitácora -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center gap-3">
            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <h3 class="text-lg font-bold text-gray-800">Bitácora de Stock</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-left">Fecha</th>
                        <th class="px-6 py-4 text-left">Tipo de Movimiento</th>
                        <th class="px-6 py-4 text-left">Referencia</th>
                        <th class="px-6 py-4 text-center">Cantidad</th>
                        <th class="px-6 py-4 text-center">Stock Anterior</th>
                        <th class="px-6 py-4 text-center">Stock Posterior</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($producto->bitacoraMovimientosStock as $movimiento)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-500 whitespace-nowrap">
                            {{ $movimiento->fecha_movimiento ? $movimiento->fecha_movimiento->format('d/m/Y H:i') : $movimiento->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-800 uppercase text-xs">
                            @if(str_contains(strtolower($movimiento->origen), 'entrada'))
                                <span class="text-green-600">{{ $movimiento->origen }}</span>
                            @elseif(str_contains(strtolower($movimiento->origen), 'salida') || str_contains(strtolower($movimiento->origen), 'merma'))
                                <span class="text-red-600">{{ $movimiento->origen }}</span>
                            @else
                                <span class="text-blue-600">{{ $movimiento->origen }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ $movimiento->referencia ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-center font-bold">
                            @if($movimiento->stock_despues > $movimiento->stock_antes)
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full font-bold">+{{ abs($movimiento->cantidad) }}</span>
                            @elseif($movimiento->stock_despues < $movimiento->stock_antes)
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full font-bold">-{{ abs($movimiento->cantidad) }}</span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full font-bold">{{ $movimiento->cantidad }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center text-gray-500 font-medium">
                            {{ $movimiento->stock_antes }}
                        </td>
                        <td class="px-6 py-4 text-center text-[#1E3A8A] font-bold">
                            {{ $movimiento->stock_despues }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-slate-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <p class="text-sm font-medium">No hay movimientos registrados para este producto.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
