@extends('layouts.app')

@section('title', 'Almacén')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" style="background:#F2F4F7; font-family: 'Poppins', sans-serif;">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Gestión de Almacén Central</h1>
        <button class="inline-flex items-center gap-2 bg-[#108981] hover:bg-[#0e746a] text-white px-4 py-2 rounded shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            + Registrar Entrada
        </button>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#1E3A8A] text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium">Código (SKU)</th>
                        <th class="px-4 py-3 text-left text-sm font-medium">Descripción del Insumo</th>
                        <th class="px-4 py-3 text-left text-sm font-medium">Categoría</th>
                        <th class="px-4 py-3 text-left text-sm font-medium">Stock Actual</th>
                        <th class="px-4 py-3 text-left text-sm font-medium">Mínimo Requerido</th>
                        <th class="px-4 py-3 text-center text-sm font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">

    @forelse($items as $producto)

        <tr class="{{ $producto->stock_actual < $producto->stock_minimo ? 'bg-red-50' : ($producto->tiene_caducidad ? 'bg-yellow-50' : '') }}">

            <td class="px-4 py-4 text-sm text-gray-600">
                {{ $producto->codigo_barras }}
            </td>

            <td class="px-4 py-4 text-sm text-gray-800 font-medium">
                {{ $producto->nombre }}
            </td>

            <td class="px-4 py-4 text-sm text-gray-600">
                {{ $producto->categoria_id ?? '—' }}
            </td>

            <td class="px-4 py-4 text-sm">
                @if($producto->stock_actual < $producto->stock_minimo)
                    <span class="text-red-600 font-semibold">
                        {{ $producto->stock_actual }}
                    </span>
                    <span class="ml-1 text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full">
                        Stock bajo
                    </span>
                @elseif($producto->tiene_caducidad)
                    <span class="text-yellow-700 font-semibold">
                        {{ $producto->stock_actual }}
                    </span>
                    <span class="ml-1 text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full">
                        ⚠️ Caduca
                    </span>
                @else
                    <span class="text-green-600 font-semibold">
                        {{ $producto->stock_actual }}
                    </span>
                @endif
            </td>

            <td class="px-4 py-4 text-sm text-gray-600">
                {{ $producto->stock_minimo }}
            </td>

            <td class="px-4 py-4 text-center">
                <button class="text-[#1E3A8A] mr-2" title="Editar">
                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h-1a2 2 0 00-2 2v1m4-3l6 6M16 7l1 1"></path>
                    </svg>
                </button>
                <button class="text-red-600 mr-2" title="Eliminar">
                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <button class="text-gray-500" title="Ver historial">
                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"></path>
                    </svg>
                </button>
            </td>

        </tr>

    @empty
        <tr>
            <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                No hay productos registrados aún.
            </td>
        </tr>
    @endforelse

</tbody>
            </table>
        </div>
    </div>

</div>

@endsection
