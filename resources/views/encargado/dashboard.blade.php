@extends('layouts.app')

@section('content')
<div class="p-6 bg-[#F2F4F7] min-h-screen">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-[#1E3A8A] font-poppins">Panel de Control: Almacén</h1>
        <span class="bg-[#108981] text-white px-4 py-2 rounded-lg text-sm">Rol: Encargado de Almacén</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-red-500">
            <p class="text-gray-500 text-sm">Insumos Críticos</p>
            <h3 class="text-2xl font-bold">12</h3>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-yellow-500">
            <p class="text-gray-500 text-sm">Próximos a Caducar</p>
            <h3 class="text-2xl font-bold">8</h3>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
            <p class="text-gray-500 text-sm">Total Insumos</p>
            <h3 class="text-2xl font-bold">145</h3>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-[#1E3A8A] text-white">
                <tr>
                    <th class="p-4">Insumo</th>
                    <th class="p-4">Existencia</th>
                    <th class="p-4">Estado Stock</th>
                    <th class="p-4">Caducidad</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 font-poppins text-sm">
                <tr class="hover:bg-gray-50">
                    <td class="p-4 font-semibold">Guantes de látex (M)</td>
                    <td class="p-4">12 unidades</td>
                    <td class="p-4 text-red-600 font-bold uppercase text-xs italic">Bajo Stock</td>
                    <td class="p-4 text-gray-600">2027-10-15</td>
                </tr>
                <tr class="hover:bg-gray-50 bg-yellow-50">
                    <td class="p-4 font-semibold">Alcohol Isopropílico 1L</td>
                    <td class="p-4">45 unidades</td>
                    <td class="p-4 text-green-600">Suficiente</td>
                    <td class="p-4 text-yellow-700 font-bold">⚠️ 2026-05-10 (Próximo)</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="p-4 font-semibold">Jeringas 5ml</td>
                    <td class="p-4">300 unidades</td>
                    <td class="p-4 text-green-600">Suficiente</td>
                    <td class="p-4 text-gray-600">2028-02-20</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection