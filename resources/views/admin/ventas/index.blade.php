@extends('layouts.app')

@section('title', 'Ventas')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" style="background:#F2F4F7; font-family: 'Poppins', sans-serif;">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Punto de Venta - Simulador</h1>
        <div class="w-1/3 md:w-1/4">
            <input type="search" placeholder="Buscar insumos..." class="w-full px-3 py-2 rounded border border-gray-200" />
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Left: Catalog (2/3) -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow p-4">
                <h2 class="text-lg font-medium mb-4 text-[#1E3A8A]">Catálogo de Insumos</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Card example -->
                    <div class="border border-gray-100 rounded-lg p-4 bg-white shadow-sm flex flex-col justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-800">Termómetro Digital</h3>
                            <p class="text-sm text-gray-500">$12.00</p>
                        </div>
                        <div class="mt-3">
                            <button class="w-full bg-[#1E3A8A] text-white px-3 py-2 rounded">Añadir</button>
                        </div>
                    </div>

                    <div class="border border-gray-100 rounded-lg p-4 bg-white shadow-sm flex flex-col justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-800">Tensiómetro</h3>
                            <p class="text-sm text-gray-500">$45.00</p>
                        </div>
                        <div class="mt-3">
                            <button class="w-full bg-[#1E3A8A] text-white px-3 py-2 rounded">Añadir</button>
                        </div>
                    </div>

                    <div class="border border-gray-100 rounded-lg p-4 bg-white shadow-sm flex flex-col justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-800">Paquete de Gasas</h3>
                            <p class="text-sm text-gray-500">$3.50</p>
                        </div>
                        <div class="mt-3">
                            <button class="w-full bg-[#1E3A8A] text-white px-3 py-2 rounded">Añadir</button>
                        </div>
                    </div>

                    <div class="border border-gray-100 rounded-lg p-4 bg-white shadow-sm flex flex-col justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-800">Guantes de látex (M)</h3>
                            <p class="text-sm text-gray-500">$0.15 c/u</p>
                        </div>
                        <div class="mt-3">
                            <button class="w-full bg-[#1E3A8A] text-white px-3 py-2 rounded">Añadir</button>
                        </div>
                    </div>

                    <div class="border border-gray-100 rounded-lg p-4 bg-white shadow-sm flex flex-col justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-800">Mascarilla KN95</h3>
                            <p class="text-sm text-gray-500">$1.20</p>
                        </div>
                        <div class="mt-3">
                            <button class="w-full bg-[#1E3A8A] text-white px-3 py-2 rounded">Añadir</button>
                        </div>
                    </div>

                    <div class="border border-gray-100 rounded-lg p-4 bg-white shadow-sm flex flex-col justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-800">Oxímetro de pulso</h3>
                            <p class="text-sm text-gray-500">$120.00</p>
                        </div>
                        <div class="mt-3">
                            <button class="w-full bg-[#1E3A8A] text-white px-3 py-2 rounded">Añadir</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Ticket (1/3) -->
        <div>
            <div class="bg-white rounded-lg shadow p-4 flex flex-col h-full">
                <h2 class="text-lg font-medium mb-4 text-[#1E3A8A]">Resumen de Venta</h2>

                <div class="flex-1 mb-4 overflow-y-auto">
                    <ul class="space-y-3">
                        <li class="flex justify-between">
                            <span>Termómetro Digital x1</span>
                            <span>$12.00</span>
                        </li>
                        <li class="flex justify-between">
                            <span>Paquete de Gasas x2</span>
                            <span>$7.00</span>
                        </li>
                    </ul>
                </div>

                <div class="border-t pt-3">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Subtotal</span>
                        <span>$19.00</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>IVA (16%)</span>
                        <span>$3.04</span>
                    </div>
                    <div class="flex justify-between text-lg font-semibold mt-2">
                        <span>Total</span>
                        <span>$22.04</span>
                    </div>

                    <button class="mt-4 w-full bg-[#108981] text-white py-3 rounded text-lg">Finalizar Venta</button>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
