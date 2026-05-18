<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestión de Insumos Médicos</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #F2F4F7; }
        .sidebar-item { transition: all 0.2s ease-in-out; }
        .sidebar-item:hover, .sidebar-item.active { background-color: rgba(255, 255, 255, 0.1); border-left: 4px solid #34D399; }
        .sidebar-item.active { background-color: rgba(255, 255, 255, 0.15); border-left: 4px solid #10B981; }
        
        /* Sistema de dimensiones del Sidebar */
        #sidebar { width: 260px; }
        #main-content { transition: margin-left 0.3s ease; margin-left: 260px; }
        #topbar { transition: width 0.3s ease, left 0.3s ease; width: calc(100% - 260px); left: 260px; }
        
        /* MODO COLAPSADO */
        .sidebar-collapsed-mode #sidebar { width: 80px !important; }
        .sidebar-collapsed-mode #main-content { margin-left: 80px !important; }
        .sidebar-collapsed-mode #topbar { width: calc(100% - 80px) !important; left: 80px !important; }
        
        .sidebar-collapsed #brand-text, .sidebar-collapsed .menu-text, .sidebar-collapsed .menu-section { display: none; }
        .sidebar-collapsed .sidebar-item { justify-content: center; padding-left: 0; padding-right: 0; }
        .sidebar-collapsed .sidebar-brand { justify-content: center; padding-left: 0; padding-right: 0; }
        .sidebar-collapsed .sidebar-item svg { margin-left: 0; margin-right: 0; }
    </style>
</head>
<body class="text-gray-800 antialiased overflow-x-hidden">

    {{-- 1. SIDEBAR (MENÚ LATERAL AZUL) --}}
    <aside id="sidebar" class="fixed top-0 left-0 h-screen bg-[#1E3A8A] text-white z-50 flex flex-col transition-all duration-300 overflow-x-hidden overflow-y-auto shadow-xl">
        
        @auth
        @php 
            $role = strtolower(Auth::user()->rol ?? ''); 
            $rutaLogo = route('admin.index');
            if($role === 'cajero') $rutaLogo = route('cajero.dashboard');
            elseif($role === 'encargado') $rutaLogo = route('almacen.productos');
        @endphp

        <!-- Logo -->
        <a href="{{ $rutaLogo }}" class="sidebar-brand flex items-center gap-3 px-6 py-5 border-b border-white/10 hover:bg-white/5 transition-colors">
            <div class="bg-white/20 p-2 rounded-lg shrink-0">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </div>
            <div id="brand-text" class="flex flex-col whitespace-nowrap overflow-hidden">
                <span class="text-xs font-bold text-white tracking-widest uppercase leading-tight">Proveedora de</span>
                <span class="text-xs font-bold text-white tracking-widest uppercase leading-tight">Insumos Médicos</span>
            </div>
        </a>

        <!-- Navegación -->
        <nav class="flex-1 py-4 space-y-1 pb-10">
            
            {{-- SECCIÓN: ADMINISTRACIÓN GENERAL --}}
            @if(in_array($role, ['dueno', 'administrador', 'cajero', 'encargado']))
            <div class="px-6 py-2 mt-2 text-[10px] font-bold text-blue-300/70 uppercase tracking-widest menu-section whitespace-nowrap">Administración</div>
            
                @if(in_array($role, ['dueno', 'administrador']))
                <a href="{{ route('admin.index') }}" class="sidebar-item {{ request()->routeIs('admin.index') ? 'active' : '' }} flex items-center px-6 py-3 border-l-4 border-transparent text-gray-200">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6"/></svg>
                    <span class="menu-text ml-4 font-medium tracking-wide">Panel Central</span>
                </a>
                @elseif($role === 'cajero')
                <a href="{{ route('cajero.dashboard') }}" class="sidebar-item {{ request()->routeIs('cajero.*') ? 'active' : '' }} flex items-center px-6 py-3 border-l-4 border-transparent text-gray-200">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6"/></svg>
                    <span class="menu-text ml-4 font-medium tracking-wide">Panel</span>
                </a>
                @elseif($role === 'encargado')
                <a href="{{ route('almacen.dashboard') }}" class="sidebar-item {{ request()->routeIs('almacen.dashboard') ? 'active' : '' }} flex items-center px-6 py-3 border-l-4 border-transparent text-gray-200">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6"/></svg>
                    <span class="menu-text ml-4 font-medium tracking-wide">Panel de Inicio</span>
                </a>
                @endif
            @endif

            {{-- SECCIÓN: CATÁLOGO MAESTRO (Administrativo y Compras) --}}
            @if(in_array($role, ['dueno', 'encargado', 'administrador']))
            <div class="px-6 py-2 mt-4 text-[10px] font-bold text-blue-300/70 uppercase tracking-widest menu-section whitespace-nowrap">Catálogo Maestro</div>
            
            <a href="{{ route('almacen.productos') }}" class="sidebar-item {{ request()->routeIs('almacen.productos*') && !request()->routeIs('almacen.productos.create') ? 'active' : '' }} flex items-center px-6 py-3 border-l-4 border-transparent text-gray-200">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                <span class="menu-text ml-4 font-medium tracking-wide">Lista de Productos</span>
            </a>
            <a href="{{ route('almacen.productos.create') }}" class="sidebar-item {{ request()->routeIs('almacen.productos.create') ? 'active' : '' }} flex items-center px-6 py-3 border-l-4 border-transparent text-gray-200">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="menu-text ml-4 font-medium tracking-wide">Nuevo Producto</span>
            </a>

                @if(in_array($role, ['dueno', 'administrador']))
                <a href="{{ route('admin.proveedores.index') }}" class="sidebar-item {{ request()->routeIs('admin.proveedores.*') ? 'active' : '' }} flex items-center px-6 py-3 border-l-4 border-transparent text-gray-200">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M15 11a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span class="menu-text ml-4 font-medium tracking-wide">Proveedores</span>
                </a>
                @endif
            @endif

            {{-- SECCIÓN: LOGÍSTICA REAL (Almacén, Entradas y Salidas) --}}
            @if(in_array($role, ['dueno', 'administrador', 'encargado']))
            <div class="px-6 py-2 mt-4 text-[10px] font-bold text-blue-300/70 uppercase tracking-widest menu-section whitespace-nowrap">Logística y Almacén</div>
            
                @if(in_array($role, ['dueno', 'administrador']))
                <a href="{{ route('admin.almacen.index') }}" class="sidebar-item {{ request()->routeIs('admin.almacen.*') ? 'active' : '' }} flex items-center px-6 py-3 border-l-4 border-transparent text-gray-200">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 7l3 13h12l3-13M8 11h8"/></svg>
                    <span class="menu-text ml-4 font-medium tracking-wide">Inventario Físico</span>
                </a>
                @endif
            <a href="{{ route('almacen.entradas') }}" class="sidebar-item {{ request()->routeIs('almacen.entradas*') ? 'active' : '' }} flex items-center px-6 py-3 border-l-4 border-transparent text-gray-200">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13l-3 3m0 0l-3-3m3 3V8m0 13a9 9 0 110-18 9 9 0 010 18z"/></svg>
                <span class="menu-text ml-4 font-medium tracking-wide">Reg. Entradas</span>
            </a>
            <a href="{{ route('almacen.salidas') }}" class="sidebar-item {{ request()->routeIs('almacen.salidas*') ? 'active' : '' }} flex items-center px-6 py-3 border-l-4 border-transparent text-gray-200">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"/></svg>
                <span class="menu-text ml-4 font-medium tracking-wide">Reg. Salidas / Mermas</span>
            </a>
            @endif

            {{-- SECCIÓN: COMERCIAL (Ventas) --}}
            @if(in_array($role, ['dueno', 'administrador', 'cajero']))
            <div class="px-6 py-2 mt-4 text-[10px] font-bold text-blue-300/70 uppercase tracking-widest menu-section whitespace-nowrap">Comercial</div>
            
            <a href="{{ in_array($role, ['dueno','administrador']) ? route('admin.ventas.index') : route('cajero.ventas.index') }}" class="sidebar-item {{ request()->routeIs('*.ventas.*') ? 'active' : '' }} flex items-center px-6 py-3 border-l-4 border-transparent text-gray-200">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2M3 21h18"/></svg>
                <span class="menu-text ml-4 font-medium tracking-wide">Punto de Venta</span>
            </a>
            @endif

            {{-- SECCIÓN: GESTIÓN DE CAJA (Solo Cajero) --}}
            @if($role === 'cajero')
            <div class="px-6 py-2 mt-4 text-[10px] font-bold text-blue-300/70 uppercase tracking-widest menu-section whitespace-nowrap">Gestión de Caja</div>
            
            @php
                $turnoAbierto = \App\Models\TurnoCaja::where('usuario_id', Auth::id())->where('estado', 'Abierto')->exists();
            @endphp
            
            <a href="{{ route('cajero.caja.index') }}" class="sidebar-item {{ request()->routeIs('cajero.caja.*') ? 'active' : '' }} flex items-center justify-between px-6 py-3 border-l-4 border-transparent text-gray-200">
                <div class="flex items-center">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span class="menu-text ml-4 font-medium tracking-wide">Control de Turno</span>
                </div>
                <div class="flex items-center justify-center menu-text">
                    @if($turnoAbierto)
                        <span class="flex w-2.5 h-2.5 bg-emerald-400 rounded-full shadow-[0_0_8px_rgba(52,211,153,0.8)]" title="Caja Abierta"></span>
                    @else
                        <span class="flex w-2.5 h-2.5 bg-red-400 rounded-full shadow-[0_0_8px_rgba(248,113,113,0.8)]" title="Caja Cerrada"></span>
                    @endif
                </div>
            </a>
            
            <!-- Enlace directo al mismo panel (sección gastos) -->
            <a href="{{ route('cajero.caja.index') }}" class="sidebar-item flex items-center px-6 py-2 border-l-4 border-transparent text-gray-300 hover:text-white pl-12 menu-text">
                <svg class="w-4 h-4 shrink-0 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                <span class="ml-3 text-sm tracking-wide">Registrar Gasto</span>
            </a>
            @endif

            {{-- SECCIÓN: SISTEMA (Reportes y Usuarios) --}}
            @if(in_array($role, ['dueno', 'administrador']))
            <div class="px-6 py-2 mt-4 text-[10px] font-bold text-blue-300/70 uppercase tracking-widest menu-section whitespace-nowrap">Sistema</div>
            
            <a href="{{ route('admin.usuarios.index') }}" class="sidebar-item {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }} flex items-center px-6 py-3 border-l-4 border-transparent text-gray-200">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <span class="menu-text ml-4 font-medium tracking-wide">Usuarios</span>
            </a>
            <a href="{{ route('admin.reportes.index') }}" class="sidebar-item {{ request()->routeIs('admin.reportes.*') ? 'active' : '' }} flex items-center px-6 py-3 border-l-4 border-transparent text-gray-200">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span class="menu-text ml-4 font-medium tracking-wide">Reportes</span>
            </a>
            @endif
            
            {{-- CERRAR SESIÓN --}}
            <div class="mt-8 pt-4 border-t border-white/10">
                <form method="POST" action="{{ route('logout') }}" class="m-0 p-0 w-full">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" 
                       class="sidebar-item flex items-center px-6 py-3 border-l-4 border-transparent text-red-300 hover:text-white hover:bg-red-500/20 w-full">
                        <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span class="menu-text ml-4 font-medium tracking-wide">Cerrar sesión</span>
                    </a>
                </form>
            </div>
        </nav>
        @endauth
    </aside>

    {{-- 2. TOPBAR (BARRA SUPERIOR) --}}
    <header id="topbar" class="fixed top-0 h-16 bg-white shadow-sm z-40 flex items-center justify-between px-6">
        <div class="flex items-center">
            <button id="toggleMenu" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-blue-900 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
            </button>
        </div>

        @auth
        <div class="flex items-center gap-4">
            <span class="text-sm font-semibold text-gray-700 hidden sm:block">
                {{ ucfirst(Auth::user()->rol ?? 'Dueño') }} | <span class="text-gray-500 font-medium">{{ Auth::user()->name ?? 'Usuario' }}</span>
            </span>
            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-[#1E3A8A] to-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-md border-2 border-white">
                {{ strtoupper(substr(Auth::user()->name ?? 'U',0,1)) }}
            </div>
        </div>
        @endauth
    </header>

    {{-- 3. CONTENIDO PRINCIPAL --}}
    <main id="main-content" class="pt-20 pb-10 min-h-screen">
        <div class="px-6 lg:px-8 max-w-7xl mx-auto">
            @if(session('status'))
                <div class="mb-6 p-4 rounded-lg bg-green-50 text-green-800 border border-green-200 shadow-sm flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-medium">{{ session('status') }}</span>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    {{-- SCRIPT PARA COLAPSAR --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btnToggle = document.getElementById('toggleMenu');
            if (btnToggle) {
                btnToggle.addEventListener('click', () => {
                    document.body.classList.toggle('sidebar-collapsed-mode');
                    document.getElementById('sidebar').classList.toggle('sidebar-collapsed');
                });
            }
            
            // Check si la pantalla es pequeña y colapsar por defecto
            if (window.innerWidth < 1024) {
                document.body.classList.add('sidebar-collapsed-mode');
                document.getElementById('sidebar').classList.add('sidebar-collapsed');
            }
        });
    </script>
</body>
</html>