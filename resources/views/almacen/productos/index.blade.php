@extends('layouts.app')

@section('titulo', 'Productos')

@section('content')
<div class="bg-white rounded-xl border shadow-sm overflow-hidden">
    <div class="p-5 border-b">
        <h3 class="text-lg font-semibold text-gray-800">Catálogo de productos</h3>
        <p class="text-sm text-gray-500 mt-1">Filtra por categoría, proveedor, fecha de caducidad o busca por nombre/sku.</p>
    </div>
    <div class="p-5 border-b grid gap-4 md:grid-cols-4">
        <div>
            <label class="form-label">Buscar</label>
            <input type="search" name="search" form="filtros-productos" value="{{ request('search') }}" class="form-input" placeholder="Nombre, SKU">
        </div>
        <div>
            <label class="form-label">Categoría</label>
            <select name="categoria" form="filtros-productos" class="form-input">
                <option value="">Todas</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>{{ $categoria->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Proveedor</label>
            <select name="proveedor" form="filtros-productos" class="form-input">
                <option value="">Todos</option>
                @foreach($proveedores as $proveedor)
                    <option value="{{ $proveedor->id }}" {{ request('proveedor') == $proveedor->id ? 'selected' : '' }}>{{ $proveedor->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Caducidad</label>
            <select name="caducidad" form="filtros-productos" class="form-input">
                <option value="">Cualquier fecha</option>
                <option value="prox7" {{ request('caducidad') == 'prox7' ? 'selected' : '' }}>Próximos 7 días</option>
                <option value="conCaducidad" {{ request('caducidad') == 'conCaducidad' ? 'selected' : '' }}>Con caducidad</option>
            </select>
        </div>
    </div>
    <div class="px-5 pb-5">
        <form id="filtros-productos" method="GET" action="{{ route('almacen.productos') }}"></form>
        <button type="submit" form="filtros-productos" class="btn-primary">Aplicar filtros</button>
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
                    <tr class="{{ $producto->stock_actual <= $producto->stock_minimo ? 'bg-red-50' : ($producto->tiene_caducidad && $producto->fecha_caducidad && $producto->fecha_caducidad <= now()->addDays(7) ? 'bg-yellow-50' : '') }}">
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
