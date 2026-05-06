@extends('layouts.app')

@section('titulo', 'Alertas de Inventario')

@section('content')
<div class="dashboard-card">
    <div class="card-header">
        <div class="card-header-left">
            <h1>Alertas de inventario</h1>
            <p>Controla los insumos críticos: stock bajo o caducidad próxima en los próximos 7 días.</p>
        </div>
        <div class="card-actions">
            <a href="{{ route('almacen.productos') }}" class="btn-success">Ver inventario completo</a>
        </div>
    </div>

    <div class="section-title">Productos en alerta</div>
    <div class="table-wrapper">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Proveedor</th>
                    <th>Stock</th>
                    <th>Alerta</th>
                    <th>Caducidad</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $producto)
                    <tr>
                        <td>{{ $producto->sku }}</td>
                        <td class="font-semibold text-slate-900">{{ $producto->nombre }}</td>
                        <td>{{ $producto->categoria?->nombre ?? 'Sin categoría' }}</td>
                        <td>{{ $producto->proveedor?->nombre ?? 'Sin proveedor' }}</td>
                        <td class="font-semibold {{ $producto->stock_actual <= $producto->stock_minimo ? 'text-red-600' : 'text-slate-700' }}">{{ $producto->stock_actual }}</td>
                        <td>
                            @if($producto->stock_actual <= $producto->stock_minimo)
                                <span class="badge-low-stock">STOCK BAJO</span>
                            @elseif($producto->tiene_caducidad && $producto->fecha_caducidad && $producto->fecha_caducidad <= now()->addDays(7))
                                <span class="badge-warning">CADUCA PRONTO</span>
                            @else
                                <span class="text-muted">OK</span>
                            @endif
                        </td>
                        <td>{{ $producto->fecha_caducidad ? date('d/m/Y', strtotime($producto->fecha_caducidad)) : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-slate-500 py-10">No hay productos en alerta actualmente.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
