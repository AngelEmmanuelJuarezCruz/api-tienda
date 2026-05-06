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
                        <!-- Example rows; backend should render results -->
                        <tr>
                            <td>Guantes de látex (M)</td>
                            <td>123456789</td>
                            <td>$2.50</td>
                            <td><button class="btn-primary" style="padding:6px 10px;font-size:14px;">Agregar al ticket</button></td>
                        </tr>
                        <tr>
                            <td>Jeringa 5 ml</td>
                            <td>987654321</td>
                            <td>$0.80</td>
                            <td><button class="btn-primary" style="padding:6px 10px;font-size:14px;">Agregar al ticket</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div>
        <div class="panel-white" style="background:#F9FAFB;">
            <h3 class="text-lg font-semibold mb-3">Ticket de Venta</h3>
            <div id="ticket-items" style="min-height:220px;">
                <p class="text-sm text-muted">No hay productos añadidos.</p>
                <!-- Example item structure:
                <div class="flex justify-between items-center py-2">
                    <div><strong>Guantes M</strong><div class="text-sm text-muted">x2</div></div>
                    <div>$5.00</div>
                </div>
                -->
            </div>

            <div style="margin-top:18px;border-top:1px solid #E6E9EF;padding-top:16px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                    <div class="text-lg font-semibold">Total:</div>
                    <div class="text-2xl font-bold">$0.00</div>
                </div>
                <button class="btn-primary" style="width:100%;background:#10B981;border:none;padding:12px 16px;font-size:16px;">Cobrar</button>
            </div>
        </div>
    </div>
</div>

@endsection
