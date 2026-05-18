@extends('layouts.app')

@section('title', 'Reportes y Auditoría')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" style="font-family: 'Poppins', sans-serif;">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Auditoría y Reportes</h1>
            <p class="text-gray-500 mt-1">Supervisión de Ventas y Cortes de Caja</p>
        </div>
        <button class="inline-flex items-center gap-2 bg-[#1E3A8A] hover:bg-blue-800 text-white px-5 py-2.5 rounded-lg shadow-md transition-colors font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Exportar PDF
        </button>
    </div>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '¡Ajuste Realizado!',
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
                    text: "{{ $errors->first() }}",
                    icon: 'error',
                    confirmButtonColor: '#EF4444'
                });
            });
        </script>
    @endif

    <!-- TABLA 1: CORTES DE CAJA -->
    <div class="mb-12">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-6 h-6 text-[#1E3A8A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Auditoría de Turnos
        </h2>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Fecha / Turno</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Cajero</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Fondo Inicial</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Ingresos</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Gastos</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Esperado</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Físico (Real)</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Diferencia</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm">
                        @forelse($cortes as $corte)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-bold text-gray-800">{{ $corte->turno }}</div>
                                <div class="text-xs text-gray-500">{{ $corte->created_at->format('d/m/Y h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-700">
                                {{ $corte->usuario->nombre ?? 'Usuario Desconocido' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-gray-500 font-medium">
                                ${{ number_format($corte->fondo_inicial, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-emerald-600">
                                ${{ number_format($corte->ingresos_ventas, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-orange-500 font-medium">
                                -${{ number_format($corte->gastos, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-gray-800">
                                ${{ number_format($corte->efectivo_esperado, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-gray-800 bg-gray-50/50">
                                {{ $corte->estado === 'Abierto' ? '---' : '$' . number_format($corte->efectivo_real, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-black">
                                @if($corte->estado === 'Abierto')
                                    <span class="text-gray-400">---</span>
                                @else
                                    @if($corte->diferencia < 0)
                                        <span class="text-red-600 bg-red-50 px-2 py-1 rounded">Faltante: ${{ number_format(abs($corte->diferencia), 2) }}</span>
                                    @elseif($corte->diferencia > 0)
                                        <span class="text-blue-600 bg-blue-50 px-2 py-1 rounded">Sobrante: +${{ number_format($corte->diferencia, 2) }}</span>
                                    @else
                                        <span class="text-emerald-600 bg-emerald-50 px-2 py-1 rounded">Cuadre Perfecto</span>
                                    @endif
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($corte->estado === 'Abierto')
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-bold text-blue-700">En Curso</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-bold text-gray-600">Cerrado</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($corte->estado === 'Cerrado')
                                    <button type="button" onclick="auditarCorte({{ $corte->id }}, {{ $corte->gastos }}, {{ $corte->efectivo_real }})" class="inline-flex items-center gap-1 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-3 py-1 rounded text-xs font-bold transition-colors shadow-sm">
                                        ✏️ Ajustar
                                    </button>
                                @else
                                    <span class="text-xs text-gray-400">No disp.</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center text-gray-500">
                                No hay cortes de caja registrados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TABLA 2: VENTAS (Últimas 100) -->
    <div>
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-6 h-6 text-[#1E3A8A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            Últimas Ventas Registradas
        </h2>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Folio</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Fecha de Operación</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Cajero</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Total Cobrado (Inc. IVA)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm">
                        @forelse($ventas as $venta)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-[#1E3A8A]">
                                {{ $venta->folio }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600 font-medium">
                                {{ $venta->fecha ? \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y h:i A') : $venta->created_at->format('d/m/Y h:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">
                                {{ $venta->usuario->nombre ?? 'Usuario Desconocido' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-black text-emerald-600 text-base">
                                ${{ number_format($venta->total, 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                No hay ventas registradas en el sistema.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
function auditarCorte(id, gastosActuales, realActual) {
    Swal.fire({
        title: 'Auditar Corte de Caja',
        html: `
            <form id="formAjuste-${id}" action="/admin/reportes/caja/${id}/ajustar" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="PUT">
                
                <div class="mb-4 text-left">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Corrección de Gastos ($)</label>
                    <p class="text-xs text-gray-500 mb-2">Ajusta el dinero real que salió de la caja.</p>
                    <input type="number" step="0.01" min="0" name="gastos" value="${gastosActuales}" class="w-full px-3 py-2 border rounded-lg focus:ring-[#1E3A8A]">
                </div>
                
                <div class="text-left">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Efectivo Físico Real ($)</label>
                    <p class="text-xs text-gray-500 mb-2">Dinero contado físicamente tras el ajuste.</p>
                    <input type="number" step="0.01" min="0" name="efectivo_real" value="${realActual}" class="w-full px-3 py-2 border rounded-lg focus:ring-[#1E3A8A]">
                </div>
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: 'Guardar Ajuste',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#1E3A8A',
        cancelButtonColor: '#6B7280',
        preConfirm: () => {
            document.getElementById('formAjuste-'+id).submit();
        }
    });
}
</script>
@endsection
