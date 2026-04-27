@extends('layouts.app')

@section('title', 'Proveedores')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" style="background:#F2F4F7; font-family: 'Poppins', sans-serif;">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Directorio de Proveedores Médicos</h1>
        <div class="flex items-center gap-3">
            <input type="search" placeholder="Buscar por nombre o categoría (ej. Laboratorios)" class="px-3 py-2 rounded border border-gray-200" />
            <button class="inline-flex items-center gap-2 bg-[#108981] hover:bg-[#0e746a] text-white px-4 py-2 rounded shadow-sm">+ Agregar Proveedor</button>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#1E3A8A] text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium">Empresa</th>
                        <th class="px-4 py-3 text-left text-sm font-medium">Contacto</th>
                        <th class="px-4 py-3 text-left text-sm font-medium">Teléfono</th>
                        <th class="px-4 py-3 text-left text-sm font-medium">Categoría de Insumos</th>
                        <th class="px-4 py-3 text-left text-sm font-medium">Ciudad</th>
                        <th class="px-4 py-3 text-center text-sm font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <tr>
                        <td class="px-4 py-4">Farmacéutica Global S.A.</td>
                        <td class="px-4 py-4">Lic. Ana Torres</td>
                        <td class="px-4 py-4">+52 55 1234 5678</td>
                        <td class="px-4 py-4">Medicamentos</td>
                        <td class="px-4 py-4">Ciudad de México</td>
                        <td class="px-4 py-4 text-center">
                            <button class="text-[#1E3A8A] mr-2" title="Llamar"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.95.68l1.1 3.3a1 1 0 01-.27 1.02l-2.2 2.2a11 11 0 005.2 5.2l2.2-2.2a1 1 0 011.02-.27l3.3 1.1a1 1 0 01.68.95V19a2 2 0 01-2 2h-1C7.716 21 3 16.284 3 10V9a2 2 0 010-4z"></path></svg></button>
                            <button class="text-gray-600 mr-2" title="Editar"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h-1a2 2 0 00-2 2v1m4-3l6 6M16 7l1 1"></path></svg></button>
                            <button class="text-red-600" title="Eliminar"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                        </td>
                    </tr>

                    <tr>
                        <td class="px-4 py-4">MedEquip Distribuciones</td>
                        <td class="px-4 py-4">Ing. Carlos Mendoza</td>
                        <td class="px-4 py-4">+52 55 8765 4321</td>
                        <td class="px-4 py-4">Equipamiento</td>
                        <td class="px-4 py-4">Monterrey</td>
                        <td class="px-4 py-4 text-center">
                            <button class="text-[#1E3A8A] mr-2" title="Llamar"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.95.68l1.1 3.3a1 1 0 01-.27 1.02l-2.2 2.2a11 11 0 005.2 5.2l2.2-2.2a1 1 0 011.02-.27l3.3 1.1a1 1 0 01.68.95V19a2 2 0 01-2 2h-1C7.716 21 3 16.284 3 10V9a2 2 0 010-4z"></path></svg></button>
                            <button class="text-gray-600 mr-2" title="Editar"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h-1a2 2 0 00-2 2v1m4-3l6 6M16 7l1 1"></path></svg></button>
                            <button class="text-red-600" title="Eliminar"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                        </td>
                    </tr>

                    <tr>
                        <td class="px-4 py-4">Insumos Quirúrgicos del Norte</td>
                        <td class="px-4 py-4">Dra. Paula Rivera</td>
                        <td class="px-4 py-4">+52 81 3344 5566</td>
                        <td class="px-4 py-4">Quirúrgicos</td>
                        <td class="px-4 py-4">Saltillo</td>
                        <td class="px-4 py-4 text-center">
                            <button class="text-[#1E3A8A] mr-2" title="Llamar"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.95.68l1.1 3.3a1 1 0 01-.27 1.02l-2.2 2.2a11 11 0 005.2 5.2l2.2-2.2a1 1 0 011.02-.27l3.3 1.1a1 1 0 01.68.95V19a2 2 0 01-2 2h-1C7.716 21 3 16.284 3 10V9a2 2 0 010-4z"></path></svg></button>
                            <button class="text-gray-600 mr-2" title="Editar"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h-1a2 2 0 00-2 2v1m4-3l6 6M16 7l1 1"></path></svg></button>
                            <button class="text-red-600" title="Eliminar"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                        </td>
                    </tr>

                    <tr>
                        <td class="px-4 py-4">Laboratorios Baxter</td>
                        <td class="px-4 py-4">Mtro. Javier López</td>
                        <td class="px-4 py-4">+52 55 9988 7766</td>
                        <td class="px-4 py-4">Laboratorios</td>
                        <td class="px-4 py-4">Guadalajara</td>
                        <td class="px-4 py-4 text-center">
                            <button class="text-[#1E3A8A] mr-2" title="Llamar"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.95.68l1.1 3.3a1 1 0 01-.27 1.02l-2.2 2.2a11 11 0 005.2 5.2l2.2-2.2a1 1 0 011.02-.27l3.3 1.1a1 1 0 01.68.95V19a2 2 0 01-2 2h-1C7.716 21 3 16.284 3 10V9a2 2 0 010-4z"></path></svg></button>
                            <button class="text-gray-600 mr-2" title="Editar"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h-1a2 2 0 00-2 2v1m4-3l6 6M16 7l1 1"></path></svg></button>
                            <button class="text-red-600" title="Eliminar"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
