@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6 max-w-2xl mx-auto mt-8">
    <h2 class="text-2xl font-bold text-[#1E3A8A] mb-6">Crear Nuevo Producto</h2>

    {{-- Validaciones visibles (Requisito de tu Objetivo 1) --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 text-green-800 border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 rounded-lg bg-red-50 text-red-800 border border-red-200">
            <ul class="list-disc pl-5 font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulario apuntando a la ruta del controlador --}}
    <form action="{{ route('almacen.productos.store') }}" method="POST">
        @csrf
        
        <div class="mb-5">
            <label class="block text-gray-700 font-semibold mb-2">Nombre del Insumo:</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}" required 
                   class="w-full rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] focus:border-transparent transition border {{ $errors->has('nombre') ? 'border-red-500' : 'border-gray-300' }}"
                   placeholder="Ej. Jeringas 5ml">
            @error('nombre')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label class="block text-gray-700 font-semibold mb-2">Categoría</label>
            <select name="categoria_id" class="w-full rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] transition border {{ $errors->has('categoria_id') ? 'border-red-500' : 'border-gray-300' }}">
                <option value="">Seleccione una categoría</option>
                @foreach($categorias ?? [] as $cat)
                    <option value="{{ $cat->id }}" {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                @endforeach
            </select>
            @error('categoria_id')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label class="block text-gray-700 font-semibold mb-2">SKU</label>
            <input type="text" name="sku" value="{{ old('sku') }}" required
                   class="w-full rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] transition border {{ $errors->has('sku') ? 'border-red-500' : 'border-gray-300' }}"
                   placeholder="Ej. SKU12345">
            @error('sku')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="mb-5">
                <label class="block text-gray-700 font-semibold mb-2">Precio de Venta</label>
                <div class="relative">
                    <span class="absolute left-3 top-3 text-gray-500">$</span>
                    <input type="number" name="precio_venta" value="{{ old('precio_venta') }}" step="0.01" required 
                           class="w-full rounded-lg p-3 pl-8 focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] focus:border-transparent transition border {{ $errors->has('precio_venta') ? 'border-red-500' : 'border-gray-300' }}"
                           placeholder="0.00">
                </div>
                @error('precio_venta')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label class="block text-gray-700 font-semibold mb-2">Stock Inicial</label>
                <input type="number" name="stock_actual" value="{{ old('stock_actual', 0) }}" required 
                       class="w-full rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] focus:border-transparent transition border {{ $errors->has('stock_actual') ? 'border-red-500' : 'border-gray-300' }}"
                       placeholder="Cantidad en almacén">
                @error('stock_actual')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('almacen.productos') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-[#1E3A8A] text-white font-semibold rounded-lg hover:bg-blue-800 transition">
                Guardar Producto
            </button>
        </div>
    </form>
</div>
@endsection