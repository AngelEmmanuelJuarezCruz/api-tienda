@extends('layouts.app')

@section('title','Registrar Entrada')

@section('content')
<div class="max-w-4xl mx-auto panel-white p-6">
    <h2 class="text-xl font-bold mb-4">Registrar Entrada de Inventario</h2>

    @if(session('success'))<div class="alert-success mb-4">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert-error mb-4">Corrige los errores del formulario.</div>@endif

    <form id="entrada-form" method="POST" action="{{ route('almacen.entradas.store') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Producto</label>
            <select id="producto-entrada" name="producto_id" class="form-input" required>
                <option value="">Seleccione un producto</option>
                @foreach(App\Models\Producto::orderBy('nombre')->get() as $p)
                    <option value="{{ $p->id }}" {{ old('producto_id') == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                @endforeach
            </select>
            @error('producto_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            <p class="text-xs text-gray-500 mt-1">El producto que ingresa desde el proveedor.</p>
        </div>

        <div class="form-group">
            <label class="form-label">Proveedor</label>
            <select id="proveedor-entrada" name="proveedor_id" class="form-input" required>
                <option value="">Seleccione un proveedor</option>
                @foreach(App\Models\Proveedor::orderBy('nombre')->get() as $prov)
                    <option value="{{ $prov->id }}" {{ old('proveedor_id') == $prov->id ? 'selected' : '' }}>{{ $prov->nombre }}</option>
                @endforeach
            </select>
            @error('proveedor_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            <p class="text-xs text-gray-500 mt-1">El proveedor debe coincidir con la factura o pedido.</p>
        </div>

        <div class="form-group">
            <label class="form-label">Cantidad</label>
            <input id="cantidad-entrada" type="number" name="cantidad" class="form-input" min="1" value="{{ old('cantidad') }}" required placeholder="Ej. 20">
            @error('cantidad')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            <p class="text-xs text-gray-500 mt-1">Introduce una cantidad positiva mayor a cero.</p>
        </div>

        <div class="form-group">
            <label class="form-label">Costo</label>
            <input id="costo-entrada" type="number" step="0.01" min="0" name="costo_adquisicion" class="form-input" value="{{ old('costo_adquisicion') }}" required placeholder="Ej. 12.50">
            @error('costo_adquisicion')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            <p class="text-xs text-gray-500 mt-1">Costo por unidad según factura del proveedor.</p>
        </div>

        <div class="form-group">
            <label class="form-label">Notas de entrada</label>
            <textarea name="notas" class="form-input" placeholder="Factura, lote, observaciones opcionales">{{ old('notas') }}</textarea>
            @error('notas')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div id="entrada-error" class="hidden text-sm text-red-700 bg-red-50 border border-red-200 rounded p-3 mb-4"></div>

        <button class="btn-primary" type="submit">Registrar Entrada</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('entrada-form');
            const productoSelect = document.getElementById('producto-entrada');
            const proveedorSelect = document.getElementById('proveedor-entrada');
            const cantidadInput = document.getElementById('cantidad-entrada');
            const costoInput = document.getElementById('costo-entrada');
            const errorBox = document.getElementById('entrada-error');

            function validateEntrada() {
                const messages = [];
                if (!productoSelect.value) {
                    messages.push('Selecciona primero un producto válido.');
                }
                if (!proveedorSelect.value) {
                    messages.push('Selecciona un proveedor válido.');
                }
                const cantidad = Number(cantidadInput.value);
                if (!cantidad || cantidad < 1) {
                    messages.push('La cantidad debe ser un número mayor a 0.');
                }
                const costo = Number(costoInput.value);
                if (isNaN(costo) || costo < 0) {
                    messages.push('El costo debe ser un valor numérico mayor o igual a 0.');
                }
                if (messages.length) {
                    errorBox.innerHTML = messages.map(msg => `<div>${msg}</div>`).join('');
                    errorBox.classList.remove('hidden');
                    return false;
                }
                errorBox.classList.add('hidden');
                return true;
            }

            form.addEventListener('submit', function (event) {
                if (!validateEntrada()) {
                    event.preventDefault();
                }
            });

            productoSelect.addEventListener('change', validateEntrada);
            proveedorSelect.addEventListener('change', validateEntrada);
            cantidadInput.addEventListener('input', validateEntrada);
            costoInput.addEventListener('input', validateEntrada);
        });
    </script>

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
