@extends('layouts.app')

@section('title','Registrar Salida')

@section('content')
<div class="max-w-4xl mx-auto panel-white p-6">
    <h2 class="text-xl font-bold mb-4">Registrar Salida de Inventario</h2>

    @if(session('success'))<div class="alert-success mb-4">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert-error mb-4">Corrige los errores del formulario.</div>@endif

    <form method="POST" action="{{ route('almacen.salidas.store') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Producto</label>
            <select id="producto-seleccion" name="producto_id" class="form-input" required>
                <option value="">Seleccione un producto</option>
                @foreach(App\Models\Producto::orderBy('nombre')->get() as $p)
                    <option value="{{ $p->id }}" data-stock="{{ $p->stock_actual }}" {{ old('producto_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->nombre }} — stock actual {{ $p->stock_actual }}
                    </option>
                @endforeach
            </select>
            @error('producto_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            <p class="text-xs text-gray-500 mt-1">Elige el producto que se retira del almacén.</p>
        </div>

        <div class="form-group">
            <label class="form-label">Cantidad</label>
            <input type="number" name="cantidad" class="form-input" min="1" value="{{ old('cantidad') }}" required placeholder="Ej. 10">
            @error('cantidad')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            <p class="text-xs text-gray-500 mt-1">Anota la cantidad exacta que sale para uso interno.</p>
        </div>

        <div class="form-group">
            <label class="form-label">Motivo</label>
            <input type="text" name="motivo" class="form-input" value="{{ old('motivo') }}" required placeholder="Ej. Uso interno, donación, merma">
            @error('motivo')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            <p class="text-xs text-gray-500 mt-1">Breve razón de la salida para control y auditoría.</p>
        </div>

        <div class="form-group">
            <label class="form-label">Justificación</label>
            <textarea name="justificacion" class="form-input" rows="4" required placeholder="Describe el uso o motivo detallado">{{ old('justificacion') }}</textarea>
            @error('justificacion')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            <p class="text-xs text-gray-500 mt-1">Explica por qué se retira este material del inventario.</p>
        </div>

        <div id="stock-error" class="hidden rounded-md p-3 bg-red-50 border border-red-200 text-red-700 text-sm mb-4"></div>
        <button class="btn-primary" type="submit">Registrar Salida</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form[action="{{ route('almacen.salidas.store') }}"]');
            const productoSelect = document.getElementById('producto-seleccion');
            const cantidadInput = document.querySelector('input[name="cantidad"]');
            const stockError = document.getElementById('stock-error');

            function getSelectedStock() {
                const selected = productoSelect.selectedOptions[0];
                return selected ? parseInt(selected.dataset.stock || '0', 10) : 0;
            }

            function validateStock() {
                const stock = getSelectedStock();
                const cantidad = parseInt(cantidadInput.value, 10);

                if (!productoSelect.value || !cantidad || cantidad <= 0) {
                    stockError.classList.add('hidden');
                    return true;
                }

                if (cantidad > stock) {
                    stockError.textContent = `La cantidad solicitada (${cantidad}) supera el stock actual disponible (${stock}). Ajusta la cantidad o elige otro producto.`;
                    stockError.classList.remove('hidden');
                    return false;
                }

                stockError.classList.add('hidden');
                return true;
            }

            productoSelect.addEventListener('change', validateStock);
            cantidadInput.addEventListener('input', validateStock);

            form.addEventListener('submit', function(event) {
                if (!validateStock()) {
                    event.preventDefault();
                }
            });
        });
    </script>

    <hr class="my-6">

    <h3 class="text-lg font-semibold mb-3">Historial de Salidas</h3>
    <table class="table">
        <thead>
            <tr><th>Fecha</th><th>Producto</th><th>Cantidad</th><th>Motivo</th><th>Justificación</th></tr>
        </thead>
        <tbody>
            @foreach($movimientos ?? [] as $m)
                <tr>
                    <td>{{ $m->fecha ?? '' }}</td>
                    <td>{{ $m->producto?->nombre ?? '' }}</td>
                    <td>{{ $m->cantidad }}</td>
                    <td>{{ $m->motivo ?? '' }}</td>
                    <td>{{ $m->justificacion ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
