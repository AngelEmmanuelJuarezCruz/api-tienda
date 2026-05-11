@extends('layouts.app')

@section('titulo', 'Catálogo de Productos')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="page-title">Catalogo master de productos</h1>
            <p class="muted text-sm">Productos con imagenes locales basadas en el scraping de Linahp.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="badge">{{ $productos->count() }} items</span>
            <a href="{{ route('almacen.productos.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nuevo producto
            </a>
        </div>
    </div>

    @if($productos->count() === 0)
        <div class="panel-white text-center py-16">
            <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
            <p class="text-sm font-medium muted">No hay productos registrados en el catalogo.</p>
        </div>
    @else
        <div class="product-grid">
            @foreach ($productos as $producto)
                <article class="product-card">
                    <div class="product-media">
                        @if ($producto->imagen_url)
                            <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}">
                        @else
                            <div class="product-placeholder">
                                <span>{{ strtoupper(substr($producto->nombre, 0, 2)) }}</span>
                                <span class="text-xs muted">Sin imagen</span>
                            </div>
                        @endif
                    </div>
                    <div class="product-body">
                        <div class="product-meta">
                            <span class="badge">{{ $producto->categoria?->nombre ?? 'Sin categoria' }}</span>
                            <span>SKU {{ $producto->sku ?? $producto->codigo_barras }}</span>
                        </div>
                        <div class="product-title">{{ $producto->nombre }}</div>
                        <div class="product-prices">
                            <span>${{ number_format((float) ($producto->precio_venta ?? 0), 2) }}</span>
                            <span class="muted text-xs">Compra ${{ number_format((float) ($producto->precio_compra ?? 0), 2) }}</span>
                        </div>
                        <div class="muted text-xs">Stock: {{ $producto->stock_actual ?? 0 }}</div>
                        <div class="product-actions">
                            <a href="{{ url('/almacen/productos/'.$producto->id) }}" class="btn btn-ghost">Ver</a>
                            <a href="{{ route('almacen.productos.edit', $producto->id) }}" class="btn btn-outline">Editar</a>
                            <form action="{{ route('almacen.productos.destroy', $producto->id) }}" method="POST" class="m-0 p-0" onsubmit="return confirm('¿Estás seguro de eliminar este producto?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-soft">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection
