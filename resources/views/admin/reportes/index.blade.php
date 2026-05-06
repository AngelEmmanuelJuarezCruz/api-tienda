@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" style="background:#F2F4F7; font-family: 'Poppins', sans-serif;">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Panel de Reportes y Estadísticas</h1>
        <button class="inline-flex items-center gap-2 bg-[#1E3A8A] hover:bg-[#163166] text-white px-4 py-2 rounded shadow-sm">Descargar PDF/Excel</button>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <select class="px-3 py-2 border border-gray-200 rounded">
                <option>Rango de fechas</option>
                <option>Últimos 7 días</option>
                <option>Últimos 30 días</option>
                <option>Este año</option>
            </select>
            <select class="px-3 py-2 border border-gray-200 rounded">
                <option>Categoría de Insumo</option>
                <option>Medicamentos</option>
                <option>Equipamiento</option>
                <option>Desechables</option>
            </select>
            <select class="px-3 py-2 border border-gray-200 rounded">
                <option>Vendedor</option>
                <option>Todos</option>
            </select>
        </div>
    </div>

    <!-- Resumen -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-600">Total Ventas Mensuales</div>
            <div class="text-2xl font-bold text-[#108981] mt-2">$124,560</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-600">Insumo Más Vendido</div>
            <div class="text-2xl font-bold mt-2">Guantes de Látex</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-600">Margen de Ganancia</div>
            <div class="text-2xl font-bold mt-2">35%</div>
        </div>
    </div>

    <!-- Gráficas -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-medium text-[#1E3A8A] mb-3">Tendencia de Ventas Anual</h3>
            <div class="h-56 bg-gradient-to-b from-white to-gray-100 rounded flex items-center justify-center text-gray-400">[Gráfica de líneas placeholder]</div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-medium text-[#1E3A8A] mb-3">Distribución de Insumos por Categoría</h3>
            <div class="h-56 bg-gradient-to-b from-white to-gray-100 rounded flex items-center justify-center text-gray-400">[Gráfica de pastel placeholder]</div>
        </div>
    </div>

</div>

@endsection
