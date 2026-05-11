@extends('layouts.app')

@section('titulo', 'Catálogo de Productos')

@section('content')
<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Catálogo Master de Productos</h1>
        <div class="ml-auto">
            <a href="{{ route('almacen.productos.create') }}" class="inline-flex items-center gap-2 bg-[#1E3A8A] hover:bg-blue-900 transition-colors text-white px-5 py-2.5 rounded-lg shadow-md font-medium text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nuevo Producto
            </a>
        </div>
    </div>

    <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-left">Código (SKU)</th>
                        <th class="px-6 py-4 text-left">Producto</th>
                        <th class="px-6 py-4 text-left">Categoría</th>
                        <th class="px-6 py-4 text-left">Precio Compra</th>
                        <th class="px-6 py-4 text-left">Precio Venta</th>
                        <th class="px-6 py-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($productos as $producto)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-slate-600 whitespace-nowrap">
                                {{ $producto->sku ?? $producto->codigo_barras }}
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-800">
                                {{ $producto->nombre }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center rounded-md bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                    {{ $producto->categoria?->nombre ?? 'Sin categoría' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-emerald-700 font-semibold">
                                ${{ number_format((float) ($producto->precio_compra ?? 0), 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-blue-700 font-semibold">
                                ${{ number_format((float) ($producto->precio_venta ?? 0), 2) }}
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap font-medium">
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
                                    <form action="{{ route('almacen.productos.destroy', $producto->id) }}" method="POST" class="inline-block m-0 p-0" onsubmit="return confirm('¿Estás seguro de eliminar este producto?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition-colors" title="Eliminar">
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
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500 bg-slate-50">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-slate-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    <p class="text-sm font-medium">No hay productos registrados en el catálogo.</p>
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
