@extends('layouts.app')

@section('title', 'Registrar Entrada de Inventario')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 mb-8">
        
        <div class="flex items-center gap-4 mb-8 border-b border-gray-100 pb-5">
            <div class="bg-[#108981]/10 p-3 rounded-xl">
                <svg class="w-6 h-6 text-[#108981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Registrar Entrada de Inventario</h2>
                <p class="text-sm text-gray-500 mt-1">Ingresa nuevos lotes de productos al almacén físico.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-green-50 text-green-800 border border-green-200 font-medium flex items-center gap-3">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 text-red-800 border border-red-200 font-medium flex items-center gap-3">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Revisa los errores del formulario.
            </div>
        @endif

        <form method="POST" action="{{ route('almacen.entradas.store') }}" id="entradaForm" novalidate>
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Producto -->
                <div class="form-group relative">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Producto <span class="text-red-500">*</span></label>
                    <select name="producto_id" required class="w-full rounded-xl border border-gray-300 p-3.5 bg-white focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] focus:border-[#1E3A8A] font-medium transition-all shadow-sm">
                        <option value="">Selecciona un producto...</option>
                        @foreach(App\Models\Producto::orderBy('nombre')->get() as $p)
                            <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                        @endforeach
                    </select>
                    <div class="error-msg hidden mt-2 text-sm text-red-500 font-medium flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Este campo es obligatorio</span>
                    </div>
                </div>

                <!-- Proveedor -->
                <div class="form-group relative">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Proveedor <span class="text-red-500">*</span></label>
                    <select name="proveedor_id" required class="w-full rounded-xl border border-gray-300 p-3.5 bg-white focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] focus:border-[#1E3A8A] font-medium transition-all shadow-sm">
                        <option value="">Selecciona un proveedor...</option>
                        @foreach(App\Models\Proveedor::orderBy('nombre')->get() as $prov)
                            <option value="{{ $prov->id }}">{{ $prov->nombre }}</option>
                        @endforeach
                    </select>
                    <div class="error-msg hidden mt-2 text-sm text-red-500 font-medium flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Este campo es obligatorio</span>
                    </div>
                </div>

                <!-- Cantidad -->
                <div class="form-group relative">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Cantidad Recibida <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <input type="number" name="cantidad" min="1" required class="w-full pl-10 pr-4 py-3.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] focus:border-[#1E3A8A] font-medium transition-all shadow-sm" placeholder="Ej. 100">
                    </div>
                    <div class="error-msg hidden mt-2 text-sm text-red-500 font-medium flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Ingresa una cantidad válida</span>
                    </div>
                </div>

                <!-- Costo -->
                <div class="form-group relative">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Costo Total / Factura <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-500 font-bold">
                            $
                        </div>
                        <input type="number" step="0.01" min="0" name="costo_adquisicion" required class="w-full pl-9 pr-4 py-3.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] focus:border-[#1E3A8A] font-medium transition-all shadow-sm" placeholder="Ej. 1500.00">
                    </div>
                    <div class="error-msg hidden mt-2 text-sm text-red-500 font-medium flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Ingresa el costo (ej. 150.50)</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-100">
                <button type="submit" id="submitBtn" class="px-8 py-3.5 bg-[#108981] text-white font-bold rounded-xl shadow-md hover:bg-teal-700 hover:shadow-lg transition-all flex items-center gap-2">
                    <span id="btnText">Registrar Entrada a Inventario</span>
                    <svg id="btnIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </button>
            </div>
        </form>
    </div>

    <!-- Historial de Entradas -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center gap-3">
            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <h3 class="text-lg font-bold text-gray-800">Historial Reciente de Entradas</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-left">Fecha</th>
                        <th class="px-6 py-4 text-left">Producto</th>
                        <th class="px-6 py-4 text-left">Proveedor</th>
                        <th class="px-6 py-4 text-center">Cantidad</th>
                        <th class="px-6 py-4 text-right">Costo Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($movimientos ?? [] as $m)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-500">{{ \Carbon\Carbon::parse($m->fecha)->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 font-bold text-[#1E3A8A]">{{ $m->producto?->nombre ?? 'N/A' }}</td>
                        <td class="px-6 py-4 font-medium text-gray-600">{{ $m->proveedor?->nombre ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full font-bold">+{{ $m->cantidad }}</span>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-800 text-right">${{ number_format($m->costo_unitario, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-500">No hay movimientos recientes registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-6px); }
        50% { transform: translateX(6px); }
        75% { transform: translateX(-6px); }
    }
    .shake-anim {
        animation: shake 0.4s ease-in-out;
    }
    .input-error {
        border-color: #EF4444 !important;
        background-color: #FEF2F2 !important;
        color: #B91C1C !important;
    }
    .input-error:focus {
        ring-color: #EF4444 !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('entradaForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const btnIcon = document.getElementById('btnIcon');

        // Función para limpiar errores visuales al escribir/seleccionar
        const clearError = (input) => {
            input.classList.remove('input-error', 'shake-anim');
            const errorMsg = input.closest('.form-group').querySelector('.error-msg');
            if (errorMsg) errorMsg.classList.add('hidden');
        };

        // Escuchar cambios en todos los inputs
        form.querySelectorAll('input, select').forEach(input => {
            input.addEventListener('input', () => clearError(input));
            input.addEventListener('change', () => clearError(input));
        });

        form.addEventListener('submit', (e) => {
            let hasErrors = false;

            // Validar campos requeridos
            form.querySelectorAll('input[required], select[required]').forEach(input => {
                if (!input.value.trim()) {
                    hasErrors = true;
                    // Agregar clases de error
                    input.classList.add('input-error');
                    
                    // Trigger shake animation (remover y agregar para re-ejecutar)
                    input.classList.remove('shake-anim');
                    void input.offsetWidth; // Force reflow
                    input.classList.add('shake-anim');

                    // Mostrar mensaje de error
                    const errorMsg = input.closest('.form-group').querySelector('.error-msg');
                    if (errorMsg) errorMsg.classList.remove('hidden');
                }
            });

            if (hasErrors) {
                e.preventDefault();
                // Animación y cambio de color en botón
                const originalBg = submitBtn.className;
                submitBtn.classList.remove('bg-[#108981]', 'hover:bg-teal-700');
                submitBtn.classList.add('bg-red-600', 'hover:bg-red-700', 'shake-anim');
                btnText.textContent = 'Revisa los errores';
                
                setTimeout(() => {
                    submitBtn.classList.remove('bg-red-600', 'hover:bg-red-700', 'shake-anim');
                    submitBtn.classList.add('bg-[#108981]', 'hover:bg-teal-700');
                    btnText.textContent = 'Registrar Entrada a Inventario';
                }, 2000);
            } else {
                // Estado de carga si todo es válido
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                btnText.textContent = 'Procesando entrada...';
                btnIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>`;
                btnIcon.classList.add('animate-spin');
            }
        });
    });
</script>
@endsection
