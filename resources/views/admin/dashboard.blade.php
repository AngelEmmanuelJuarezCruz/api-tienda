@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="px-6 py-4" style="background: transparent;">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-800" style="font-family: 'Poppins', sans-serif;">Gestion de Insumos Medicos</h1>
            <p class="text-sm text-gray-500 mt-1">Resumen operativo con datos actuales del sistema.</p>
        </div>

        <div class="metric-grid mb-6">
            <div class="metric-card" style="background-color:#EFF6FF;">
                <div class="icon" style="background:transparent">
                    <svg class="w-6 h-6 text-[#1E3A8A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 7h14l-2-7M10 21a1 1 0 100-2 1 1 0 000 2zm6 0a1 1 0 100-2 1 1 0 000 2z"></path></svg>
                </div>
                <div>
                    <p class="label">Ventas del dia</p>
                    <p class="value">${{ number_format($stats['ventas_hoy'], 2) }}</p>
                </div>
            </div>

            <div class="metric-card" style="background-color:#FFF7ED;">
                <div class="icon" style="background:transparent">
                    <svg class="w-6 h-6 text-[#FF8C42]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86A2 2 0 0021 17.08V8.92A2 2 0 0019.07 7H4.93A2 2 0 003 8.92v8.16A2 2 0 005.07 19z"></path></svg>
                </div>
                <div>
                    <p class="label">Productos bajos</p>
                    <p class="value">{{ $stats['stock_bajo'] }}</p>
                </div>
            </div>

            <div class="metric-card" style="background-color:#F0FDF4;">
                <div class="icon" style="background:transparent">
                    <svg class="w-6 h-6 text-[#108981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2"></path></svg>
                </div>
                <div>
                    <p class="label">Ingresos del mes</p>
                    <p class="value">${{ number_format($stats['ingresos_mes'], 2) }}</p>
                </div>
            </div>

            <div class="metric-card" style="background-color:#FAF5FF;">
                <div class="icon" style="background:transparent">
                    <svg class="w-6 h-6 text-[#6B21A8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7l9-4 9 4v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                </div>
                <div>
                    <p class="label">Usuarios registrados</p>
                    <p class="value">{{ $stats['usuarios'] }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="panel-white lg:col-span-2">
                <h2 class="text-lg font-medium text-gray-800 mb-3">Ventas de los ultimos 7 dias</h2>
                <div class="w-full h-72 flex items-end gap-3 px-4">
                    @foreach($ventasUltimosDias as $dia)
                        @php
                            $altura = 1.5 + (($dia['total'] / $maxVentas) * 8);
                        @endphp
                        <div class="flex-1 flex flex-col items-center justify-end gap-2 min-w-0">
                            <span class="text-[11px] font-semibold text-gray-500">${{ number_format($dia['total'], 0) }}</span>
                            <div class="w-6 bg-[#1E3A8A] rounded-t-lg" style="height:{{ $altura }}rem;"></div>
                            <p class="text-xs text-gray-500 truncate">{{ $dia['dia'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="panel-white">
                <h2 class="text-lg font-medium text-gray-800 mb-3">Productos mas vendidos</h2>
                <ul class="space-y-3 text-gray-700">
                    @forelse($productosMasVendidos as $item)
                        <li class="flex justify-between items-center gap-3">
                            <span>{{ $item->producto?->nombre ?? 'Producto eliminado' }}</span>
                            <span class="text-sm text-gray-500 whitespace-nowrap">{{ (int) $item->unidades }} unidades</span>
                        </li>
                    @empty
                        <li class="text-sm text-gray-500">Sin ventas registradas todavia.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            <div class="panel-white">
                <h2 class="text-lg font-medium text-gray-800 mb-3">Inventario reciente</h2>
                <ul class="space-y-3 text-gray-700">
                    @forelse($ultimosProductos as $producto)
                        <li class="flex justify-between items-center gap-3">
                            <div>
                                <div class="font-semibold text-slate-800">{{ $producto->nombre }}</div>
                                <div class="text-xs text-gray-500">{{ $producto->categoria?->nombre ?? 'Sin categoria' }}</div>
                            </div>
                            <span class="text-sm text-gray-500 whitespace-nowrap">Stock: {{ $producto->stock_actual }}</span>
                        </li>
                    @empty
                        <li class="text-sm text-gray-500">Sin productos registrados.</li>
                    @endforelse
                </ul>
            </div>

            <div class="panel-white">
                <h2 class="text-lg font-medium text-gray-800 mb-3">Totales del sistema</h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="rounded-lg bg-slate-50 p-4">
                        <div class="text-gray-500">Productos activos</div>
                        <div class="text-2xl font-bold text-slate-800">{{ $stats['activos'] }}</div>
                    </div>
                    <div class="rounded-lg bg-slate-50 p-4">
                        <div class="text-gray-500">Proveedores</div>
                        <div class="text-2xl font-bold text-slate-800">{{ $stats['proveedores'] }}</div>
                    </div>
                    <div class="rounded-lg bg-slate-50 p-4">
                        <div class="text-gray-500">Categorias</div>
                        <div class="text-2xl font-bold text-slate-800">{{ $stats['categorias'] }}</div>
                    </div>
                    <div class="rounded-lg bg-slate-50 p-4">
                        <div class="text-gray-500">Ventas registradas</div>
                        <div class="text-2xl font-bold text-slate-800">{{ $stats['ventas'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
