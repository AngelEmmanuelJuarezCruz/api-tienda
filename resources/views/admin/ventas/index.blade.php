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
                    @forelse ($productos as $producto)
                        <div class="border border-gray-100 rounded-lg p-4 bg-white shadow-sm flex flex-col justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $producto->nombre }}</h3>
                                <p class="text-sm text-gray-500">${{ number_format((float) $producto->precio_venta, 2) }}</p>
                            </div>
                            <div class="mt-3">
                                <button type="button" class="w-full bg-[#1E3A8A] text-white px-3 py-2 rounded">Añadir</button>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-sm text-gray-500">No hay productos activos para mostrar.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right: Ticket (1/3) -->
        <div>
            <form action="{{ route($storeRoute) }}" method="POST" class="bg-white rounded-lg shadow p-4 flex flex-col h-full">
                @csrf
                <h2 class="text-lg font-medium mb-4 text-[#1E3A8A]">Resumen de Venta</h2>

                <div class="flex-1 mb-4 overflow-y-auto">
                    <ul class="space-y-3">
                        @forelse ($ticketItems as $item)
                            <li class="flex justify-between">
                                <span>{{ $item['nombre'] }} x{{ $item['cantidad'] }}</span>
                                <span>${{ number_format($item['subtotal'], 2) }}</span>
                            </li>
                            <input type="hidden" name="productos[{{ $loop->index }}][producto_id]" value="{{ $item['producto_id'] }}">
                            <input type="hidden" name="productos[{{ $loop->index }}][cantidad]" value="{{ $item['cantidad'] }}">
                        @empty
                            <li class="text-sm text-gray-500">No hay artículos en el ticket.</li>
                        @endforelse
                    </ul>
                </div>

                <div class="border-t pt-3">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Subtotal</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>IVA (16%)</span>
                        <span>${{ number_format($iva, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-semibold mt-2">
                        <span>Total</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>

                    <button type="submit" class="mt-4 w-full bg-[#108981] text-white py-3 rounded text-lg">Finalizar Venta</button>
                </div>
            </form>
        </div>

    </div>

</div>

@endsection
