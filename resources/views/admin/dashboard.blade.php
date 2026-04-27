@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
@php
    $total_productos = \App\Models\Producto::count();
    $productos_bajos = \App\Models\Producto::whereColumn('stock_actual', '<', 'stock_minimo')->count();
    $total_usuarios = \App\Models\User::count();
@endphp
<div class="min-h-screen p-6" style="background: transparent;">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6" style="font-family: 'Poppins', sans-serif;">Gestión de Insumos Médicos</h1>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <!-- Ventas del día -->
            <div class="bg-blue-50 shadow-sm rounded-lg p-4 flex items-center space-x-4 border-l-4 border-[#1E3A8A]">
                <div class="w-12 h-12 rounded-full flex items-center justify-center bg-[#1E3A8A]/10">
                    <!-- Carrito icon -->
                    <svg class="w-6 h-6 text-[#1E3A8A]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 7h14l-2-7M10 21a1 1 0 100-2 1 1 0 000 2zm6 0a1 1 0 100-2 1 1 0 000 2z"></path></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Ventas del día</p>
                    <p class="text-xl font-semibold text-gray-900">$12,450.00</p>
                </div>
            </div>

            <!-- Productos bajos -->
            <div class="bg-orange-50 shadow-sm rounded-lg p-4 flex items-center space-x-4 border-l-4 border-[#FF8C42]">
                <div class="w-12 h-12 rounded-full flex items-center justify-center bg-[#FF8C42]/10">
                    <!-- Alert icon -->
                    <svg class="w-6 h-6 text-[#FF8C42]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86A2 2 0 0021 17.08V8.92A2 2 0 0019.07 7H4.93A2 2 0 003 8.92v8.16A2 2 0 005.07 19z"></path></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Productos bajos</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $productos_bajos }}</p>
                </div>
            </div>

            <!-- Ingresos del mes -->
            <div class="bg-green-50 shadow-sm rounded-lg p-4 flex items-center space-x-4 border-l-4 border-[#108981]">
                <div class="w-12 h-12 rounded-full flex items-center justify-center bg-[#108981]/10">
                    <!-- Bill icon -->
                    <svg class="w-6 h-6 text-[#108981]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2"></path></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Ingresos del mes</p>
                    <p class="text-xl font-semibold text-gray-900">$98,750.00</p>
                </div>
            </div>

            <!-- Órdenes pendientes -->
            <div class="bg-gray-50 shadow-sm rounded-lg p-4 flex items-center space-x-4 border-l-4 border-gray-700">
                <div class="w-12 h-12 rounded-full flex items-center justify-center bg-gray-100">
                    <!-- Box icon -->
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7l9-4 9 4v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Órdenes pendientes</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $total_usuarios }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white rounded-lg p-6 border-dashed border-2 border-gray-200">
                <h2 class="text-lg font-medium text-gray-800 mb-3">Ventas de los últimos 7 días</h2>
                <div class="w-full h-64 flex items-end gap-3 px-4">
                    <!-- 7 barras estáticas con diferentes alturas -->
                    <div class="flex-1 flex items-end justify-center">
                        <div class="w-6 bg-[#1E3A8A] rounded-t-lg" style="height:5rem;"></div>
                        <p class="text-xs text-gray-500 mt-2">Lun</p>
                    </div>
                    <div class="flex-1 flex items-end justify-center">
                        <div class="w-6 bg-[#1E3A8A] rounded-t-lg" style="height:8rem;"></div>
                        <p class="text-xs text-gray-500 mt-2">Mar</p>
                    </div>
                    <div class="flex-1 flex items-end justify-center">
                        <div class="w-6 bg-[#1E3A8A] rounded-t-lg" style="height:6.5rem;"></div>
                        <p class="text-xs text-gray-500 mt-2">Mié</p>
                    </div>
                    <div class="flex-1 flex items-end justify-center">
                        <div class="w-6 bg-[#1E3A8A] rounded-t-lg" style="height:9rem;"></div>
                        <p class="text-xs text-gray-500 mt-2">Jue</p>
                    </div>
                    <div class="flex-1 flex items-end justify-center">
                        <div class="w-6 bg-[#1E3A8A] rounded-t-lg" style="height:7rem;"></div>
                        <p class="text-xs text-gray-500 mt-2">Vie</p>
                    </div>
                    <div class="flex-1 flex items-end justify-center">
                        <div class="w-6 bg-[#1E3A8A] rounded-t-lg" style="height:4.5rem;"></div>
                        <p class="text-xs text-gray-500 mt-2">Sáb</p>
                    </div>
                    <div class="flex-1 flex items-end justify-center">
                        <div class="w-6 bg-[#1E3A8A] rounded-t-lg" style="height:9.5rem;"></div>
                        <p class="text-xs text-gray-500 mt-2">Dom</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-800 mb-3">Productos más vendidos</h2>
                <ul class="space-y-3 text-gray-700">
                    <li class="flex justify-between items-center">
                        <span>Guantes de látex (M)</span>
                        <span class="text-sm text-gray-500">150 unidades</span>
                    </li>
                    <li class="flex justify-between items-center">
                        <span>Jeringa 5 ml</span>
                        <span class="text-sm text-gray-500">98 unidades</span>
                    </li>
                    <li class="flex justify-between items-center">
                        <span>Alcohol isopropílico</span>
                        <span class="text-sm text-gray-500">65 unidades</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection
