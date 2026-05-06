@extends('layouts.app')

@section('title', 'Proveedores')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="panel-white">
        <h1 class="page-title">Directorio de Proveedores Médicos</h1>

        <div style="display:flex;gap:15px;margin-bottom:20px;align-items:center;justify-content:space-between;">
            <div style="flex:1;max-width:520px;">
                <input type="search" placeholder="Buscar por nombre o categoría (ej. Laboratorios)" class="form-input" />
            </div>

            <div style="margin-left:16px;">
                <a href="{{ route('admin.proveedores.create') ?? '#' }}" class="btn-primary">+ Agregar Proveedor</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-[#1E3A8A] text-white">
                    <tr>
                        <th>Empresa</th>
                        <th>Contacto</th>
                        <th>Teléfono</th>
                        <th>Categoría de Insumos</th>
                        <th>Ciudad</th>
                        <th style="text-align:center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Farmacéutica Global S.A.</td>
                        <td>Lic. Ana Torres</td>
                        <td>+52 55 1234 5678</td>
                        <td>Medicamentos</td>
                        <td>Ciudad de México</td>
                        <td style="text-align:center">
                            <button class="btn-ghost" title="Llamar"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.95.68l1.1 3.3a1 1 0 01-.27 1.02l-2.2 2.2a11 11 0 005.2 5.2l2.2-2.2a1 1 0 011.02-.27l3.3 1.1a1 1 0 01.68.95V19a2 2 0 01-2 2h-1C7.716 21 3 16.284 3 10V9a2 2 0 010-4z"></path></svg></button>
                            <button class="btn-ghost" title="Editar"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h-1a2 2 0 00-2 2v1m4-3l6 6M16 7l1 1"></path></svg></button>
                            <button class="btn-ghost" title="Eliminar"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
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
