@extends('layouts.app')

@section('title','Crear Producto')

@section('content')
<div class="panel-white max-w-3xl mx-auto">
    <h2 class="page-title">Crear Producto</h2>

    @if(session('success'))
        <div class="alert-success mb-4">{{ session('success') }}</div>
    @endif

    <form action="{{ route('almacen.productos.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="form-label" for="nombre">Nombre del Producto</label>
                <input id="nombre" name="nombre" type="text" value="{{ old('nombre') }}" class="form-input" style="{{ $errors->has('nombre') ? 'border-color:#EF4444;' : '' }}">
                @error('nombre')
                    <p class="text-sm" style="color:#EF4444;margin-top:.35rem;">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label" for="codigo_barras">Código de Barras</label>
                <input id="codigo_barras" name="codigo_barras" type="text" value="{{ old('codigo_barras') }}" class="form-input" style="{{ $errors->has('codigo_barras') ? 'border-color:#EF4444;' : '' }}">
                @error('codigo_barras')
                    <p class="text-sm" style="color:#EF4444;margin-top:.35rem;">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label" for="precio">Precio</label>
                <input id="precio" name="precio" type="number" step="0.01" value="{{ old('precio') }}" class="form-input" style="{{ $errors->has('precio') ? 'border-color:#EF4444;' : '' }}">
                @error('precio')
                    <p class="text-sm" style="color:#EF4444;margin-top:.35rem;">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label" for="cantidad">Cantidad</label>
                <input id="cantidad" name="cantidad" type="number" step="1" value="{{ old('cantidad', 0) }}" class="form-input" style="{{ $errors->has('cantidad') ? 'border-color:#EF4444;' : '' }}">
                @error('cantidad')
                    <p class="text-sm" style="color:#EF4444;margin-top:.35rem;">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="btn-primary">Guardar</button>
            <a href="{{ route('almacen.productos') }}" class="btn-ghost">Cancelar</a>
        </div>
    </form>
</div>
@endsection
