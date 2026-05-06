@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" style="background:#F2F4F7; font-family: 'Poppins', sans-serif;">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Gestión de Usuarios y Roles</h1>
        <button class="inline-flex items-center gap-2 bg-[#108981] hover:bg-[#0e746a] text-white px-4 py-2 rounded shadow-sm">+ Registrar Nuevo Empleado</button>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="p-4 border-b">
            <input type="search" placeholder="Buscar por nombre o correo..." class="w-full md:w-1/2 px-3 py-2 rounded border border-gray-200" />
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#1E3A8A] text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium">Nombre Completo</th>
                        <th class="px-4 py-3 text-left text-sm font-medium">Correo Electrónico</th>
                        <th class="px-4 py-3 text-left text-sm font-medium">Rol</th>
                        <th class="px-4 py-3 text-left text-sm font-medium">Estado</th>
                        <th class="px-4 py-3 text-center text-sm font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">

                    <tr>
                        <td class="px-4 py-4">Dueño</td>
                        <td class="px-4 py-4">dueno@apitienda.local</td>
                        <td class="px-4 py-4">Dueño</td>
                        <td class="px-4 py-4"><span class="text-green-600 font-medium">Activo</span></td>
                        <td class="px-4 py-4 text-center">
                            <button class="text-[#1E3A8A] mr-2" title="Editar"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h-1a2 2 0 00-2 2v1m4-3l6 6M16 7l1 1"></path></svg></button>
                            <button class="text-yellow-500 mr-2" title="Restablecer contraseña"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.657 1.343-3 3-3s3 1.343 3 3M12 11v6m0 0H9m3 0h3"></path></svg></button>
                            <button class="text-red-600" title="Dar de baja"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                        </td>
                    </tr>

                    <tr>
                        <td class="px-4 py-4">Encargado</td>
                        <td class="px-4 py-4">encargado@apitienda.local</td>
                        <td class="px-4 py-4">Encargado de Almacén</td>
                        <td class="px-4 py-4"><span class="text-green-600 font-medium">Activo</span></td>
                        <td class="px-4 py-4 text-center">
                            <button class="text-[#1E3A8A] mr-2" title="Editar"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h-1a2 2 0 00-2 2v1m4-3l6 6M16 7l1 1"></path></svg></button>
                            <button class="text-yellow-500 mr-2" title="Restablecer contraseña"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.657 1.343-3 3-3s3 1.343 3 3M12 11v6m0 0H9m3 0h3"></path></svg></button>
                            <button class="text-red-600" title="Dar de baja"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                        </td>
                    </tr>

                    <tr>
                        <td class="px-4 py-4">Cajero</td>
                        <td class="px-4 py-4">cajero@apitienda.local</td>
                        <td class="px-4 py-4">Cajero</td>
                        <td class="px-4 py-4"><span class="text-gray-600 font-medium">Inactivo</span></td>
                        <td class="px-4 py-4 text-center">
                            <button class="text-[#1E3A8A] mr-2" title="Editar"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h-1a2 2 0 00-2 2v1m4-3l6 6M16 7l1 1"></path></svg></button>
                            <button class="text-yellow-500 mr-2" title="Restablecer contraseña"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.657 1.343-3 3-3s3 1.343 3 3M12 11v6m0 0H9m3 0h3"></path></svg></button>
                            <button class="text-red-600" title="Dar de baja"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
