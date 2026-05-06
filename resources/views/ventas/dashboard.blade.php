@extends('layouts.app')

@section('titulo', 'Ventas')

@section('content')
<div class="grid gap-6">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <p class="text-sm text-gray-500">Ventas registradas</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['ventas'] }}</p>
        </div>
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total vendido</p>
            <p class="mt-2 text-3xl font-bold text-green-600">$ {{ number_format($stats['total'], 2) }}</p>
        </div>
        <div class="bg-white rounded-xl border p-5 shadow-sm">
            <p class="text-sm text-gray-500">Usuarios activos</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['usuarios'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
        <div class="p-5 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Últimas ventas</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="text-left px-5 py-3">Folio</th>
                        <th class="text-left px-5 py-3">Usuario</th>
                        <th class="text-left px-5 py-3">Fecha</th>
                        <th class="text-right px-5 py-3">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($ventas as $venta)
                        <tr>
                            <td class="px-5 py-3">{{ $venta->folio }}</td>
                            <td class="px-5 py-3">{{ $venta->usuario?->name ?? 'Sin usuario' }}</td>
                            <td class="px-5 py-3">{{ optional($venta->fecha)->format('Y-m-d H:i') }}</td>
                            <td class="px-5 py-3 text-right font-medium">$ {{ number_format((float) $venta->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-gray-500">Aún no hay ventas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
