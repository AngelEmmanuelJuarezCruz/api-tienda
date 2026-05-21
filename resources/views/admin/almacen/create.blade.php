@extends('layouts.app')

@section('content')
<div class="bg-white rounded-2xl shadow-xl p-8 max-w-4xl mx-auto mt-8 border border-gray-100">
    <div class="flex items-center gap-4 mb-8 border-b border-gray-100 pb-5">
        <div class="bg-blue-50 p-3 rounded-xl">
            <svg class="w-6 h-6 text-[#1E3A8A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Crear Nuevo Producto</h2>
            <p class="text-sm text-gray-500 mt-1">Ingresa los datos para registrar un insumo en el catálogo maestro.</p>
        </div>
    </div>

    {{-- Formulario con 'novalidate' para desactivar los tooltips nativos del navegador --}}
    <form id="productForm" action="{{ route('almacen.productos.store') }}" method="POST" novalidate>
        @csrf

        {{-- Alertas Globales del Backend --}}
        @if($errors->any())
            <div class="mb-8 p-4 rounded-xl bg-red-50 border border-red-200 flex gap-4 items-start shadow-sm transition-all">
                <svg class="w-6 h-6 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <h3 class="text-sm font-bold text-red-800">No pudimos guardar el producto</h3>
                    <ul class="list-disc pl-5 mt-2 text-sm text-red-700 space-y-1 font-medium">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
            
            {{-- Input: Nombre --}}
            <div class="form-group">
                <label class="block text-sm font-bold text-gray-700 mb-2">Nombre del Insumo <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="text" name="nombre" value="{{ old('nombre') }}" data-required="true"
                           class="w-full rounded-xl border {{ $errors->has('nombre') ? 'border-red-400 bg-red-50 ring-2 ring-red-100' : 'border-gray-300' }} p-3.5 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium text-gray-800"
                           placeholder="Ej. Jeringas 5ml">
                    <div class="error-icon absolute right-4 top-3.5 text-red-500 hidden">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <p class="error-text text-xs text-red-500 font-semibold mt-2 hidden">Este campo es obligatorio.</p>
                @error('nombre') <p class="text-xs text-red-600 font-semibold mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- Input: SKU --}}
            <div class="form-group">
                <label class="block text-sm font-bold text-gray-700 mb-2">SKU / Código <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="text" name="sku" value="{{ old('sku') }}" data-required="true"
                           class="w-full rounded-xl border {{ $errors->has('sku') ? 'border-red-400 bg-red-50 ring-2 ring-red-100' : 'border-gray-300' }} p-3.5 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium text-gray-800"
                           placeholder="Ej. SKU12345">
                    <div class="error-icon absolute right-4 top-3.5 text-red-500 hidden">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <p class="error-text text-xs text-red-500 font-semibold mt-2 hidden">Este campo es obligatorio.</p>
                @error('sku') <p class="text-xs text-red-600 font-semibold mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- Input: Categoría --}}
            <div class="form-group">
                <label class="block text-sm font-bold text-gray-700 mb-2">Categoría <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select name="categoria_id" data-required="true"
                            class="w-full rounded-xl border {{ $errors->has('categoria_id') ? 'border-red-400 bg-red-50 ring-2 ring-red-100' : 'border-gray-300' }} p-3.5 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium text-gray-800 bg-white">
                        <option value="">Seleccione una categoría</option>
                        @foreach($categorias ?? [] as $cat)
                            <option value="{{ $cat->id }}" {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                    <div class="absolute right-4 top-4 text-gray-400 pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                <p class="error-text text-xs text-red-500 font-semibold mt-2 hidden">Debes seleccionar una categoría.</p>
                @error('categoria_id') <p class="text-xs text-red-600 font-semibold mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- Input: Proveedor --}}
            <div class="form-group">
                <label class="block text-sm font-bold text-gray-700 mb-2">Proveedor <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select name="proveedor_id" data-required="true"
                            class="w-full rounded-xl border {{ $errors->has('proveedor_id') ? 'border-red-400 bg-red-50 ring-2 ring-red-100' : 'border-gray-300' }} p-3.5 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium text-gray-800 bg-white">
                        <option value="">Seleccione un proveedor</option>
                        @foreach($proveedores ?? [] as $prov)
                            <option value="{{ $prov->id }}" {{ old('proveedor_id') == $prov->id ? 'selected' : '' }}>{{ $prov->nombre }}</option>
                        @endforeach
                    </select>
                    <div class="absolute right-4 top-4 text-gray-400 pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                <p class="error-text text-xs text-red-500 font-semibold mt-2 hidden">Debes seleccionar un proveedor.</p>
                @error('proveedor_id') <p class="text-xs text-red-600 font-semibold mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- Input: Precio Venta --}}
            <div class="form-group">
                <label class="block text-sm font-bold text-gray-700 mb-2">Precio de Venta <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute left-4 top-3.5 text-gray-500 font-bold">$</span>
                    <input type="number" name="precio_venta" value="{{ old('precio_venta') }}" step="0.01" data-required="true"
                           class="w-full rounded-xl border {{ $errors->has('precio_venta') ? 'border-red-400 bg-red-50 ring-2 ring-red-100' : 'border-gray-300' }} p-3.5 pl-8 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-bold text-gray-900"
                           placeholder="0.00">
                    <div class="error-icon absolute right-4 top-3.5 text-red-500 hidden">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <p class="error-text text-xs text-red-500 font-semibold mt-2 hidden">Ingresa un precio válido mayor a 0.</p>
                @error('precio_venta') <p class="text-xs text-red-600 font-semibold mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- Input: Fecha de Caducidad --}}
            <div class="form-group">
                <label class="block text-sm font-bold text-gray-700 mb-2">Fecha de Caducidad <span class="text-gray-400 font-normal text-xs">(Opcional)</span></label>
                <div class="relative">
                    <input type="date" name="fecha_caducidad" value="{{ old('fecha_caducidad') }}"
                           class="w-full rounded-xl border {{ $errors->has('fecha_caducidad') ? 'border-red-400 bg-red-50 ring-2 ring-red-100' : 'border-gray-300' }} p-3.5 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium text-gray-800">
                </div>
                @error('fecha_caducidad') <p class="text-xs text-red-600 font-semibold mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- Input: Stock --}}
            <div class="form-group md:col-span-2">
                <label class="block text-sm font-bold text-gray-700 mb-2">Stock Inicial <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="number" name="stock_actual" value="{{ old('stock_actual', 0) }}" data-required="true"
                           class="w-full rounded-xl border {{ $errors->has('stock_actual') ? 'border-red-400 bg-red-50 ring-2 ring-red-100' : 'border-gray-300' }} p-3.5 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium text-gray-800"
                           placeholder="Cantidad en almacén">
                    <div class="error-icon absolute right-4 top-3.5 text-red-500 hidden">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <p class="error-text text-xs text-red-500 font-semibold mt-2 hidden">El stock no puede estar vacío.</p>
                @error('stock_actual') <p class="text-xs text-red-600 font-semibold mt-2">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 mt-10 pt-6 border-t border-gray-100">
            <a href="{{ route('almacen.productos') }}" class="px-6 py-3 border-2 border-gray-200 rounded-xl text-gray-600 font-bold hover:bg-gray-50 hover:border-gray-300 transition-all focus:ring-4 focus:ring-gray-100">
                Cancelar
            </a>
            <button type="submit" id="btnSubmit" class="px-8 py-3 bg-[#1E3A8A] text-white font-bold rounded-xl hover:bg-blue-900 transition-all shadow-[0_4px_14px_0_rgba(30,58,138,0.39)] hover:shadow-[0_6px_20px_rgba(30,58,138,0.23)] focus:ring-4 focus:ring-blue-500/50 flex items-center gap-2">
                Guardar Producto
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('productForm');
    const btnSubmit = document.getElementById('btnSubmit');

    form.addEventListener('submit', function(e) {
        let hasError = false;
        
        // Limpiar errores previos visuales
        document.querySelectorAll('.error-text, .error-icon').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('[data-required="true"]').forEach(input => {
            input.classList.remove('border-red-400', 'bg-red-50', 'ring-2', 'ring-red-100');
        });

        // Validar cada campo requerido
        document.querySelectorAll('[data-required="true"]').forEach(input => {
            if (!input.value.trim() || (input.type === 'number' && input.value < 0)) {
                hasError = true;
                const group = input.closest('.form-group');
                
                // Aplicar estilos de error al input
                input.classList.add('border-red-400', 'bg-red-50', 'ring-2', 'ring-red-100');
                input.classList.remove('border-gray-300');
                
                // Mostrar icono de error
                const icon = group.querySelector('.error-icon');
                if(icon) icon.classList.remove('hidden');
                
                // Mostrar mensaje de error
                const errorText = group.querySelector('.error-text');
                if(errorText) errorText.classList.remove('hidden');
                
                // Animación de sacudida
                input.animate([
                    { transform: 'translateX(0)' },
                    { transform: 'translateX(-5px)' },
                    { transform: 'translateX(5px)' },
                    { transform: 'translateX(-5px)' },
                    { transform: 'translateX(0)' }
                ], { duration: 400, easing: 'ease-in-out' });
            }
        });

        if (hasError) {
            e.preventDefault(); // Evitar envío nativo para mostrar nuestra UI
            
            // Animación del botón guardar
            const originalText = btnSubmit.innerHTML;
            btnSubmit.innerHTML = 'Revisa los errores';
            btnSubmit.classList.add('bg-red-600');
            btnSubmit.classList.remove('bg-[#1E3A8A]', 'hover:bg-blue-900');
            
            setTimeout(() => {
                btnSubmit.innerHTML = originalText;
                btnSubmit.classList.remove('bg-red-600');
                btnSubmit.classList.add('bg-[#1E3A8A]', 'hover:bg-blue-900');
            }, 2500);
        }
    });

    // Validar en tiempo real (limpiar error cuando el usuario escribe)
    document.querySelectorAll('[data-required="true"]').forEach(input => {
        input.addEventListener('input', function() {
            if (this.value.trim() && !(this.type === 'number' && this.value < 0)) {
                const group = this.closest('.form-group');
                this.classList.remove('border-red-400', 'bg-red-50', 'ring-2', 'ring-red-100');
                this.classList.add('border-gray-300');
                
                const icon = group.querySelector('.error-icon');
                if(icon) icon.classList.add('hidden');
                
                const errorText = group.querySelector('.error-text');
                if(errorText) errorText.classList.add('hidden');
            }
        });
    });
});
</script>
@endsection