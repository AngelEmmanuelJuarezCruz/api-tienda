@extends('layouts.app')

@section('title', 'Almacén Central')

@section('content')
<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Gestión de Almacén Central</h1>
        <div class="ml-auto">
            <a href="{{ route('almacen.entradas') }}" class="inline-flex items-center gap-2 bg-[#108981] hover:bg-teal-700 transition-colors text-white px-5 py-2.5 rounded-lg shadow-md font-medium text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Registrar Entrada
            </a>
        </div>
    </div>

    <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-left">Código (SKU)</th>
                        <th class="px-6 py-4 text-left">Descripción del Insumo</th>
                        <th class="px-6 py-4 text-left">Categoría</th>
                        <th class="px-6 py-4 text-left">Stock Actual</th>
                        <th class="px-6 py-4 text-left">Mínimo Requerido</th>
                        <th class="px-6 py-4 text-left">Último Movimiento</th>
                        <th class="px-6 py-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                @forelse($productos as $producto)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-slate-600 whitespace-nowrap">
                            {{ $producto->sku ?? $producto->codigo_barras }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-800 font-medium">
                            {{ $producto->nombre }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 whitespace-nowrap">
                            {{ $producto->categoria?->nombre ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-sm whitespace-nowrap font-medium">
                            @if($producto->stock_actual < $producto->stock_minimo)
                                <span class="inline-flex items-center rounded-md bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">
                                    {{ $producto->stock_actual }} Stock bajo
                                </span>
                            @elseif($producto->tiene_caducidad)
                                <span class="inline-flex items-center rounded-md bg-yellow-100 px-2.5 py-1 text-xs font-semibold text-yellow-800">
                                    {{ $producto->stock_actual }} Caduca
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-md bg-[#108981] px-3 py-1 text-xs font-semibold text-white shadow-sm">
                                    {{ $producto->stock_actual }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 whitespace-nowrap">
                            {{ $producto->stock_minimo }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 whitespace-nowrap">
                            {{ $producto->bitacoraMovimientosStock->last() ? $producto->bitacoraMovimientosStock->last()->created_at->format('d/m/Y H:i') : 'Sin movimientos' }}
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ url('/almacen/productos/'.$producto->id) }}" class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 hover:text-blue-800 hover:bg-blue-100 transition-colors px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm border border-blue-200" title="Ver Historial de Movimientos">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                    Historial
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-slate-500 bg-slate-50">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-slate-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <p class="text-sm font-medium">No hay productos registrados en el almacén.</p>
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
