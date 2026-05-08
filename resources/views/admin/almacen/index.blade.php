@extends('layouts.app')

@section('title', 'Almacén')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" style="background:#F2F4F7; font-family: 'Poppins', sans-serif;">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Gestión de Almacén Central</h1>
        <div class="ml-auto">
            <button class="inline-flex items-center gap-2 bg-[#108981] hover:bg-teal-700 transition-colors text-white px-5 py-2.5 rounded-lg shadow-md font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Registrar Entrada
            </button>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold">Código (SKU)</th>
                        <th class="px-6 py-4 text-left font-semibold">Descripción del Insumo</th>
                        <th class="px-6 py-4 text-left font-semibold">Categoría</th>
                        <th class="px-6 py-4 text-left font-semibold">Stock Actual</th>
                        <th class="px-6 py-4 text-left font-semibold">Mínimo Requerido</th>
                        <th class="px-6 py-4 text-center font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
    @forelse($productos as $producto)

        <tr class="hover:bg-gray-50 border-b border-gray-200 transition-colors">

            <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                {{ $producto->sku ?? $producto->codigo_barras }}
            </td>

            <td class="px-6 py-4 text-sm text-gray-800 font-medium">
                {{ $producto->nombre }}
            </td>

            <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                {{ $producto->categoria->nombre ?? '—' }}
            </td>

            <td class="px-6 py-4 text-sm whitespace-nowrap">
                @if($producto->stock_actual < $producto->stock_minimo)
                    <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">
                        {{ $producto->stock_actual }} Stock bajo
                    </span>
                @elseif($producto->tiene_caducidad)
                    <span class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">
                        {{ $producto->stock_actual }} Caduca
                    </span>
                @else
                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                        {{ $producto->stock_actual }}
                    </span>
                @endif
            </td>

            <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                {{ $producto->stock_minimo }}
            </td>

            <td class="px-6 py-4 text-center whitespace-nowrap text-sm font-medium">
                <div class="flex items-center justify-center gap-3">
                    <a href="{{ url('/almacen/productos/'.$producto->id) }}" class="text-blue-600 hover:text-blue-800 transition-colors" title="Ver Detalle">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </a>
                    <a href="{{ route('almacen.productos.edit', $producto->id) }}" class="text-yellow-500 hover:text-yellow-700 transition-colors" title="Editar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </a>
                    <form action="{{ route('almacen.productos.destroy', $producto->id) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este producto?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" title="Eliminar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </td>

        </tr>

    @empty
        <tr>
            <td colspan="6" class="px-6 py-8 text-center text-gray-500 bg-gray-50">
                <div class="flex flex-col items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    <p class="text-sm">No hay productos registrados en el almacén.</p>
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
