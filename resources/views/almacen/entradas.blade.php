@extends('layouts.app')

@section('title','Registrar Entrada')

@section('content')
<div class="max-w-4xl mx-auto panel-white p-6">
    <h2 class="text-xl font-bold mb-4">Registrar Entrada de Inventario</h2>

    @if(session('success'))<div class="alert-success mb-4">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert-error mb-4">Corrige los errores del formulario.</div>@endif

    <form method="POST" action="{{ route('almacen.entradas.store') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Producto</label>
            <select name="producto_id" class="form-input">
                @foreach(App\Models\Producto::all() as $p)
                    <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Proveedor</label>
            <select name="proveedor_id" class="form-input">
                @foreach(App\Models\Proveedor::all() as $prov)
                    <option value="{{ $prov->id }}">{{ $prov->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Cantidad</label>
            <input type="number" name="cantidad" class="form-input" required>
        </div>

        <div class="form-group">
            <label class="form-label">Costo</label>
            <input type="number" step="0.01" name="costo_adquisicion" class="form-input" required>
        </div>

        <button class="btn-primary" type="submit">Registrar Entrada</button>
    </form>

    <hr class="my-6">

    <h3 class="text-lg font-semibold mb-3">Historial de Entradas</h3>
    <table class="table">
        <thead>
            <tr><th>Fecha</th><th>Producto</th><th>Proveedor</th><th>Cantidad</th><th>Costo</th></tr>
        </thead>
        <tbody>
            @foreach($movimientos ?? [] as $m)
                <tr>
                    <td>{{ $m->fecha ?? '' }}</td>
                    <td>{{ $m->producto?->nombre ?? '' }}</td>
                    <td>{{ $m->proveedor?->nombre ?? '' }}</td>
                    <td>{{ $m->cantidad }}</td>
                    <td>{{ $m->costo_unitario ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
