@extends('layouts.app')

@section('title', 'Control de Caja')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8" style="font-family: 'Poppins', sans-serif;">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Control de Caja y Turnos</h1>
        <p class="text-gray-500 mt-1">Apertura, gastos y corte de caja de tu jornada.</p>
    </div>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '¡Éxito!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonColor: '#10B981'
                });
            });
        </script>
    @endif

    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Aviso',
                    html: `
                        <ul class="text-sm text-left list-disc pl-5 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    `,
                    icon: 'error',
                    confirmButtonColor: '#EF4444'
                });
            });
        </script>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Columna Izquierda: Estado Actual -->
        <div class="lg:col-span-2">
            @if($turnoActual)
                <!-- TURNO ABIERTO -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-6">
                    <div class="bg-[#1E3A8A] px-6 py-4 flex justify-between items-center">
                        <h2 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Turno en Curso
                        </h2>
                        <span class="bg-blue-800 text-blue-100 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide border border-blue-700">
                            {{ $turnoActual->turno }}
                        </span>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
                            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                                <p class="text-xs font-bold text-slate-500 uppercase">Fondo Inicial</p>
                                <p class="text-xl font-black text-gray-800 mt-1">${{ number_format($turnoActual->fondo_inicial, 2) }}</p>
                            </div>
                            <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-100">
                                <p class="text-xs font-bold text-emerald-600 uppercase">Ingresos Ventas</p>
                                <p class="text-xl font-black text-emerald-700 mt-1">${{ number_format($turnoActual->ingresos_ventas, 2) }}</p>
                            </div>
                            <div class="bg-orange-50 rounded-xl p-4 border border-orange-100">
                                <p class="text-xs font-bold text-orange-600 uppercase">Gastos</p>
                                <p class="text-xl font-black text-orange-700 mt-1">-${{ number_format($turnoActual->gastos, 2) }}</p>
                            </div>
                            <div class="bg-blue-50 rounded-xl p-4 border border-blue-200 shadow-inner">
                                <p class="text-xs font-bold text-[#1E3A8A] uppercase">Efectivo en Caja</p>
                                <p class="text-xl font-black text-[#1E3A8A] mt-1">${{ number_format($turnoActual->efectivo_esperado, 2) }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Registrar Gasto -->
                            <div class="border border-gray-200 rounded-xl p-5 bg-gray-50/50">
                                <h3 class="font-bold text-gray-800 mb-3 text-sm flex items-center gap-1">
                                    <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    Registrar Gasto de Caja
                                </h3>
                                <form action="{{ route('cajero.caja.gasto') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="block text-xs text-gray-500 mb-1">Monto ($)</label>
                                        <input type="number" step="0.01" min="0.01" name="monto_gasto" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-[#1E3A8A] focus:border-[#1E3A8A]">
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-xs text-gray-500 mb-1">Descripción</label>
                                        <input type="text" name="descripcion_gasto" required placeholder="Ej. Compra de agua" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-[#1E3A8A] focus:border-[#1E3A8A]">
                                    </div>
                                    <button type="submit" class="w-full bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-bold py-2 rounded-lg text-sm transition-colors">
                                        Guardar Gasto
                                    </button>
                                </form>
                            </div>

                            <!-- Cerrar Turno -->
                            <div class="border border-gray-200 rounded-xl p-5 bg-gray-50/50">
                                <h3 class="font-bold text-gray-800 mb-3 text-sm flex items-center gap-1">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Corte de Caja (Cerrar Turno)
                                </h3>
                                <form action="{{ route('cajero.caja.cerrar') }}" method="POST" id="formCerrarTurno">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Efectivo Físico Contado ($)</label>
                                        <p class="text-xs text-gray-500 mb-2 leading-tight">Ingresa la cantidad exacta de billetes y monedas que hay físicamente en la caja.</p>
                                        <input type="number" step="0.01" min="0" name="efectivo_real" required class="w-full px-3 py-2 border-2 border-[#1E3A8A] rounded-lg text-lg font-bold text-[#1E3A8A] focus:ring-[#1E3A8A] focus:border-[#1E3A8A]">
                                    </div>
                                    <button type="submit" class="w-full bg-[#1E3A8A] hover:bg-blue-800 text-white font-bold py-2.5 rounded-lg text-sm transition-colors shadow-md">
                                        Realizar Corte
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- ABRIR TURNO -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mb-6 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 mb-4">
                        <svg class="w-8 h-8 text-[#1E3A8A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Caja Cerrada</h2>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">Para comenzar a cobrar y registrar ventas en el Punto de Venta, necesitas abrir tu turno primero.</p>
                    
                    <form action="{{ route('cajero.caja.abrir') }}" method="POST" class="max-w-sm mx-auto text-left">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Nombre del Turno</label>
                            <select name="turno" required class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-[#1E3A8A] focus:border-[#1E3A8A]">
                                <option value="Turno Matutino">Turno Matutino</option>
                                <option value="Turno Vespertino">Turno Vespertino</option>
                                <option value="Turno Nocturno">Turno Nocturno</option>
                            </select>
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Fondo Inicial ($)</label>
                            <input type="number" step="0.01" min="0" name="fondo_inicial" value="0.00" required class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-[#1E3A8A] focus:border-[#1E3A8A]">
                        </div>
                        <button type="submit" class="w-full bg-[#108981] hover:bg-teal-700 text-white font-bold py-3 rounded-xl transition-colors shadow-md">
                            Abrir Turno
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <!-- Columna Derecha: Historial -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sticky top-4">
                <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Tus Últimos Turnos</h3>
                
                <div class="space-y-3">
                    @forelse($historial as $turno)
                        <div class="p-3 rounded-lg border {{ $turno->estado === 'Abierto' ? 'border-blue-200 bg-blue-50' : 'border-gray-100 bg-gray-50' }}">
                            <div class="flex justify-between items-start mb-1">
                                <span class="text-xs font-bold text-gray-800">{{ $turno->turno }}</span>
                                @if($turno->estado === 'Abierto')
                                    <span class="text-[10px] bg-blue-200 text-blue-800 font-bold px-2 rounded-full">Activo</span>
                                @else
                                    <span class="text-[10px] bg-gray-200 text-gray-600 font-bold px-2 rounded-full">Cerrado</span>
                                @endif
                            </div>
                            <div class="text-[11px] text-gray-500 mb-2">
                                {{ $turno->created_at->format('d M Y') }}
                            </div>
                            
                            @if($turno->estado === 'Cerrado')
                                <div class="flex justify-between items-center text-xs mt-2 pt-2 border-t border-gray-200">
                                    <span class="text-gray-600">Diferencia:</span>
                                    @if($turno->diferencia < 0)
                                        <span class="font-bold text-red-600">Faltante: ${{ number_format(abs($turno->diferencia), 2) }}</span>
                                    @elseif($turno->diferencia > 0)
                                        <span class="font-bold text-blue-600">Sobrante: +${{ number_format($turno->diferencia, 2) }}</span>
                                    @else
                                        <span class="font-bold text-emerald-600">Cuadró</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">No hay historial de turnos.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const formCerrar = document.getElementById('formCerrarTurno');
    if(formCerrar) {
        formCerrar.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Cerrar Turno?',
                text: '¿Estás seguro de cerrar el turno? Ya no podrás realizar ventas bajo este turno.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Sí, realizar corte',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    formCerrar.submit();
                }
            });
        });
    }
});
</script>
@endsection
@endsection
