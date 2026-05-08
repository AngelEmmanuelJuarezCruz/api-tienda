@extends('layouts.app')

@section('title', 'Proveedores')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="proveedoresCrud()">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
        
        <div class="flex items-center gap-4 mb-8 border-b border-gray-100 pb-5">
            <div class="bg-blue-50 p-3 rounded-xl">
                <svg class="w-6 h-6 text-[#1E3A8A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Directorio de Proveedores Médicos</h1>
                <p class="text-sm text-gray-500 mt-1">Administra los proveedores de insumos de la tienda.</p>
            </div>
        </div>

        {{-- Alertas --}}
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-green-50 text-green-800 border border-green-200 font-medium flex items-center gap-3">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 text-red-800 border border-red-200 font-medium flex items-center gap-3">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ $errors->first() }}
            </div>
        @endif

        <div class="flex flex-col md:flex-row gap-4 mb-8 items-center justify-between">
            <div class="flex-1 max-w-lg w-full relative">
                <form action="{{ route('admin.proveedores.index') }}" method="GET">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Buscar por nombre o contacto..." class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#1E3A8A] focus:border-[#1E3A8A] font-medium transition-all" />
                </form>
            </div>
            <div class="ml-auto shrink-0">
                <button @click="openModal()" class="px-6 py-3 bg-[#108981] text-white font-bold rounded-xl shadow-md hover:bg-teal-700 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Agregar Proveedor
                </button>
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-100 shadow-sm">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-left">Empresa</th>
                        <th class="px-6 py-4 text-left">Contacto</th>
                        <th class="px-6 py-4 text-left">Teléfono</th>
                        <th class="px-6 py-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($proveedores as $prov)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-800">{{ $prov->nombre }}</td>
                        <td class="px-6 py-4 font-medium text-gray-600">{{ $prov->contacto ?? '—' }}</td>
                        <td class="px-6 py-4 font-medium text-gray-600">{{ $prov->telefono ?? '—' }}</td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-3">
                                {{-- Llamar (con modal interactivo) --}}
                                @if($prov->telefono)
                                <button @click.prevent="openPhoneModal('{{ addslashes($prov->telefono) }}', '{{ addslashes($prov->nombre) }}')" class="p-2 text-green-600 bg-green-50 rounded-lg hover:bg-green-100 transition-colors" title="Llamar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.95.68l1.1 3.3a1 1 0 01-.27 1.02l-2.2 2.2a11 11 0 005.2 5.2l2.2-2.2a1 1 0 011.02-.27l3.3 1.1a1 1 0 01.68.95V19a2 2 0 01-2 2h-1C7.716 21 3 16.284 3 10V9a2 2 0 010-4z"></path></svg>
                                </button>
                                @else
                                <span class="p-2 text-gray-300"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.95.68l1.1 3.3a1 1 0 01-.27 1.02l-2.2 2.2a11 11 0 005.2 5.2l2.2-2.2a1 1 0 011.02-.27l3.3 1.1a1 1 0 01.68.95V19a2 2 0 01-2 2h-1C7.716 21 3 16.284 3 10V9a2 2 0 010-4z"></path></svg></span>
                                @endif
                                
                                {{-- Editar --}}
                                <button @click="openModal({{ $prov->id }}, '{{ addslashes($prov->nombre) }}', '{{ addslashes($prov->contacto) }}', '{{ addslashes($prov->telefono) }}')" class="p-2 text-yellow-500 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors" title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                
                                {{-- Eliminar --}}
                                <button @click="confirmDelete({{ $prov->id }}, '{{ addslashes($prov->nombre) }}')" class="p-2 text-red-500 bg-red-50 rounded-lg hover:bg-red-100 transition-colors" title="Eliminar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-slate-500">
                            No se encontraron proveedores registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL CREAR/EDITAR --}}
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm" x-cloak style="display: none;">
        <div @click.away="closeModal()" class="bg-white rounded-2xl shadow-2xl p-8 max-w-lg w-full mx-4 transform transition-all"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-90 translate-y-4">
            
            <h3 class="text-2xl font-bold text-[#1E3A8A] mb-6" x-text="isEdit ? 'Editar Proveedor' : 'Nuevo Proveedor'"></h3>
            
            <form :action="formAction" method="POST">
                @csrf
                <template x-if="isEdit">
                    @method('PUT')
                </template>

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Empresa <span class="text-red-500">*</span></label>
                        <input type="text" name="nombre" x-model="formData.nombre" required class="w-full rounded-xl border border-gray-300 p-3.5 focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] focus:border-[#1E3A8A] font-medium transition-all" placeholder="Ej. Farmacéutica Global">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nombre de Contacto</label>
                        <input type="text" name="contacto" x-model="formData.contacto" class="w-full rounded-xl border border-gray-300 p-3.5 focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] focus:border-[#1E3A8A] font-medium transition-all" placeholder="Ej. Ana Torres">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Teléfono</label>
                        <input type="text" name="telefono" x-model="formData.telefono" class="w-full rounded-xl border border-gray-300 p-3.5 focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] focus:border-[#1E3A8A] font-medium transition-all" placeholder="Ej. +52 55 1234 5678">
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                    <button type="button" @click="closeModal()" class="px-6 py-3 text-gray-600 font-bold hover:bg-gray-100 rounded-xl transition-colors">Cancelar</button>
                    <button type="submit" class="px-8 py-3 bg-[#1E3A8A] text-white font-bold rounded-xl shadow-md hover:bg-blue-900 transition-colors flex items-center gap-2">
                        <span x-text="isEdit ? 'Actualizar Proveedor' : 'Guardar Proveedor'"></span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL ELIMINAR --}}
    <div x-show="showDelete" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm" x-cloak style="display: none;">
        <div @click.away="showDelete = false" class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 text-center transform transition-all"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
            
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-50 mb-6 border-4 border-red-100">
                <svg class="h-10 w-10 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Eliminar Proveedor</h3>
            <p class="text-gray-600 mb-8 font-medium">¿Estás seguro de eliminar a <strong x-text="deleteName" class="text-gray-900"></strong>? <br><span class="text-sm text-red-500 block mt-2">Esta acción no se puede deshacer y puede afectar productos vinculados.</span></p>
            
            <form :action="'/admin/proveedores/' + deleteId" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex flex-col sm:flex-row justify-center gap-3">
                    <button type="button" @click="showDelete = false" class="w-full sm:w-auto px-6 py-3 text-gray-700 font-bold bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Cancelar, mantener</button>
                    <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-red-600 text-white font-bold rounded-xl shadow-md hover:bg-red-700 transition-colors">Sí, eliminar proveedor</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL TELÉFONO --}}
    <div x-show="showPhoneModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm" x-cloak style="display: none;">
        <div @click.away="showPhoneModal = false" class="bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4 text-center transform transition-all"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-8">
            
            <h3 class="text-xl font-bold text-gray-900 mb-5" x-text="phoneName"></h3>

            <div class="bg-blue-50 rounded-xl border border-blue-100 py-5 px-3 mb-4">
                <span class="text-3xl font-bold text-[#1E3A8A] tracking-widest" x-text="phoneNumber"></span>
            </div>

            <div class="h-6 mb-5 flex items-center justify-center">
                <p x-show="copied" x-transition class="text-green-600 font-bold text-sm m-0 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    ¡Copiado!
                </p>
            </div>
            
            <div class="flex justify-center gap-3">
                <button @click="showPhoneModal = false" class="w-full px-4 py-3 text-gray-700 font-bold bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Cerrar</button>
                <button @click="copyToClipboard()" class="w-full px-4 py-3 bg-[#1E3A8A] text-white font-bold rounded-xl shadow-md hover:bg-blue-900 transition-colors flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    Copiar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Dependemos de AlpineJS para la interactividad visual fluida. Laravel Breeze o el ecosistema moderno suele incluirlo en app.js.
    // Si no está, el modal funcionará con un pequeño script inyectado pero asumimos Alpine.
    document.addEventListener('alpine:init', () => {
        Alpine.data('proveedoresCrud', () => ({
            showModal: false,
            isEdit: false,
            formAction: '{{ route('admin.proveedores.store') }}',
            formData: {
                nombre: '',
                contacto: '',
                telefono: ''
            },
            
            showDelete: false,
            deleteId: null,
            deleteName: '',

            showPhoneModal: false,
            phoneNumber: '',
            phoneClean: '',
            phoneName: '',
            copied: false,
            
            openModal(id = null, nombre = '', contacto = '', telefono = '') {
                this.isEdit = id !== null;
                if (this.isEdit) {
                    this.formAction = '/admin/proveedores/' + id;
                    this.formData = { nombre, contacto, telefono };
                } else {
                    this.formAction = '{{ route('admin.proveedores.store') }}';
                    this.formData = { nombre: '', contacto: '', telefono: '' };
                }
                this.showModal = true;
                
                // Set focus al input después de abrir animado
                setTimeout(() => { document.querySelector('input[name="nombre"]').focus(); }, 100);
            },
            
            closeModal() {
                this.showModal = false;
            },
            
            confirmDelete(id, nombre) {
                this.deleteId = id;
                this.deleteName = nombre;
                this.showDelete = true;
            },

            openPhoneModal(phone, name) {
                this.phoneNumber = phone;
                this.phoneClean = phone.replace(/[^0-9+]/g, '');
                this.phoneName = name;
                this.copied = false;
                this.showPhoneModal = true;
            },

            copyToClipboard() {
                const text = this.phoneNumber;
                // Intento moderno para HTTPS
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(text).then(() => {
                        this.showCopiedSuccess();
                    }).catch(err => console.error("Error al copiar: ", err));
                } else {
                    // Fallback para HTTP (desarrollo local Laragon)
                    const textArea = document.createElement("textarea");
                    textArea.value = text;
                    textArea.style.position = "fixed";
                    textArea.style.left = "-999999px";
                    textArea.style.top = "-999999px";
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();
                    try {
                        document.execCommand('copy');
                        this.showCopiedSuccess();
                    } catch (err) {
                        console.error("No se pudo copiar", err);
                    }
                    textArea.remove();
                }
            },
            showCopiedSuccess() {
                this.copied = true;
                setTimeout(() => { this.copied = false; }, 2000);
            }
        }))
    })
    
    // Si AlpineJS no está cargado en el layout, inyectarlo por CDN automáticamente:
    if (typeof Alpine === 'undefined') {
        const script = document.createElement('script');
        script.src = "https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js";
        script.defer = true;
        document.head.appendChild(script);
    }
</script>
@endsection
