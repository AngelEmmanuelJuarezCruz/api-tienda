@extends('layouts.app')

@section('title','Punto de Venta')

@section('content')
<div class="grid" style="grid-template-columns: 70% 30%; gap:20px; align-items:start;">
    <div>
        <div class="panel-white">
            <label class="form-label" for="pos-search">Buscar productos por nombre o código</label>
            <input id="pos-search" type="search" placeholder="Ej: Guantes o 123456789" class="form-input" style="width:100%;padding:12px 16px;font-size:16px;margin-bottom:12px;">

            <div class="overflow-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Código</th>
                            <th>Precio</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($productos as $producto)
                            <tr>
                                <td>{{ $producto->nombre }}</td>
                                <td>{{ $producto->codigo_barras ?? $producto->sku }}</td>
                                <td>${{ number_format((float) $producto->precio_venta, 2) }}</td>
                                <td><button class="btn-primary" style="padding:6px 10px;font-size:14px;" type="button">Agregar al ticket</button></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No hay productos activos para mostrar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div>
        <form class="panel-white" style="background:#F9FAFB;" action="{{ route($storeRoute) }}" method="POST">
            @csrf
            <h3 class="text-lg font-semibold mb-3">Ticket de Venta</h3>
            <div id="ticket-items" style="min-height:220px;">
                @forelse ($ticketItems as $item)
                    <div class="flex justify-between items-center py-2">
                        <div><strong>{{ $item['nombre'] }}</strong><div class="text-sm text-muted">x{{ $item['cantidad'] }}</div></div>
                        <div>${{ number_format($item['subtotal'], 2) }}</div>
                    </div>
                    <input type="hidden" name="productos[{{ $loop->index }}][producto_id]" value="{{ $item['producto_id'] }}">
                    <input type="hidden" name="productos[{{ $loop->index }}][cantidad]" value="{{ $item['cantidad'] }}">
                @empty
                    <p class="text-sm text-muted">No hay productos añadidos.</p>
                @endforelse
            </div>

            <div style="margin-top:18px;border-top:1px solid #E6E9EF;padding-top:16px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                    <div class="text-lg font-semibold">Total:</div>
                    <div class="text-2xl font-bold">${{ number_format($total, 2) }}</div>
                </div>
                <button class="btn-primary" style="width:100%;background:#10B981;border:none;padding:12px 16px;font-size:16px;" type="submit">Cobrar</button>
            </div>
        </form>
    </div>
</div>

@endsection
