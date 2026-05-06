@extends('layouts.app')

@section('title','Editar Producto')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded shadow">
    <h2 class="text-xl font-bold mb-4">Editar Producto</h2>

    @if(session('success'))
        <div class="alert-success mb-4">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert-error mb-4">Por favor corrige los errores del formulario.</div>
    @endif

    <form action="{{ route('almacen.productos.update', $producto->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label class="form-label">Nombre</label>
            <input class="form-input" type="text" name="nombre" value="{{ old('nombre',$producto->nombre) }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">Precio</label>
            <input class="form-input" type="number" step="0.01" name="precio" value="{{ old('precio',$producto->precio_venta) }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">Stock</label>
            <input class="form-input" type="number" name="stock" value="{{ old('stock',$producto->stock_actual) }}" required>
        </div>

        <button class="btn-primary" type="submit">Actualizar</button>
    </form>
</div>
@endsection
