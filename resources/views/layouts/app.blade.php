<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Insumos Médicos</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background-color: #F2F4F7; margin: 0; padding: 0;">

    <div class="flex min-h-screen">

        {{-- 1. SIDEBAR (MENÚ LATERAL AZUL) --}}
        <aside id="sidebar" style="position: fixed; top: 0; left: 0; width: 250px; height: 100vh; background-color: #1E3A8A; z-index: 1000; overflow-y: auto; transition: all 0.3s ease;">
            
            @auth
            @php 
                $role = strtolower(Auth::user()->rol ?? ''); 
                
                // Redirigimos el logo dependiendo de quién inicie sesión
                $rutaLogo = route('admin.index'); // Por defecto para dueño/admin
                if($role === 'cajero') {
                    $rutaLogo = route('cajero.dashboard'); // Si es cajero va a su panel
                } elseif($role === 'encargado') {
                    $rutaLogo = route('almacen.productos'); // Si es encargado va a productos
                }
            @endphp

            <!-- Logo Dinámico -->
            <a href="{{ $rutaLogo }}" class="sidebar-brand" style="display: flex; align-items: center; gap: 12px; padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); text-decoration: none;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="flex-shrink: 0;">
                    <rect x="10" y="3" width="4" height="18" rx="1" fill="#FFFFFF"/>
                    <rect x="3" y="10" width="18" height="4" rx="1" fill="#FFFFFF"/>
                </svg>
                <div class="brand-text" style="display: flex; flex-direction: column;">
                    <span style="font-size:12px;font-weight:700;color:#ffffff;text-transform:uppercase;line-height:1.2;">PROVEEDORA DE</span>
                    <span style="font-size:12px;font-weight:700;color:#ffffff;text-transform:uppercase;line-height:1.2;">INSUMOS MÉDICOS</span>
                </div>
            </a>

            <!-- Navegación -->
            <nav class="nav-items" style="padding: 20px 0;">
                
                {{-- PANEL GENERAL: Solo lo ve el Dueño o Administrador --}}
                @if(in_array($role, ['dueno', 'administrador']))
                <a href="{{ route('admin.index') }}" class="nav-item {{ request()->routeIs('admin.index') ? 'active' : '' }}" style="display:flex; padding:12px 20px; align-items:center;">
                    <svg width="20" height="20" class="w-5 h-5 text-white" fill="none" stroke="#FFFFFF" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6"/></svg>
                    <span class="ml-2" style="color:#FFFFFF; margin-left:10px;">Panel</span>
                </a>
                @endif

                {{-- PANEL CAJERO: Solo lo ve el Cajero --}}
                @if($role === 'cajero')
                <a href="{{ route('cajero.dashboard') }}" class="nav-item {{ request()->routeIs('cajero.*') ? 'active' : '' }}" style="display:flex; padding:12px 20px; align-items:center;">
                    <svg width="20" height="20" class="w-5 h-5 text-white" fill="none" stroke="#FFFFFF" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6"/></svg>
                    <span class="ml-2" style="color:#FFFFFF; margin-left:10px;">Panel</span>
                </a>
                @endif

                {{-- ALMACÉN (DASHBOARD GRÁFICAS): Solo lo ve Dueño o Administrador --}}
                @if(in_array($role, ['dueno', 'administrador']))
                <a href="{{ route('admin.almacen.index') }}" class="nav-item {{ request()->routeIs('admin.almacen.*') ? 'active' : '' }}" style="display:flex; padding:12px 20px; align-items:center;">
                    <svg width="20" height="20" class="w-5 h-5 text-white" fill="none" stroke="#FFFFFF" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 7l3 13h12l3-13"/></svg>
                    <span class="ml-2" style="color:#FFFFFF; margin-left:10px;">Almacén</span>
                </a>
                @endif

                {{-- PRODUCTOS Y CREAR PRODUCTO: Lo ven Dueño, Administrador y Encargado --}}
                @if(in_array($role, ['dueno', 'encargado', 'administrador']))
                <a href="{{ route('almacen.productos') }}" class="nav-item {{ request()->routeIs('almacen.productos*') ? 'active' : '' }}" style="display:flex; padding:12px 20px; align-items:center;">
                    <svg width="20" height="20" class="w-5 h-5 text-white" fill="none" stroke="#FFFFFF" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18v11a2 2 0 01-2 2H5a2 2 0 01-2-2zM16 3v4M8 3v4"/></svg>
                    <span class="ml-2" style="color:#FFFFFF; margin-left:10px;">Productos</span>
                </a>
                
                <a href="{{ route('almacen.alertas') }}" class="nav-item {{ request()->routeIs('almacen.alertas') ? 'active' : '' }}" style="display:flex; padding:12px 20px; align-items:center;">
                    <svg width="20" height="20" class="w-5 h-5 text-white" fill="none" stroke="#FFFFFF" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M4.93 19.07a10 10 0 1114.14 0 10 10 0 01-14.14 0z"/></svg>
                    <span class="ml-2" style="color:#FFFFFF; margin-left:10px;">Alertas</span>
                </a>

                <a href="{{ route('almacen.productos.create') }}" class="nav-item {{ request()->routeIs('almacen.productos.create') ? 'active' : '' }}" style="display:flex; padding:12px 20px; align-items:center;">
                    <svg width="20" height="20" class="w-5 h-5 text-white" fill="none" stroke="#FFFFFF" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span class="ml-2" style="color:#FFFFFF; margin-left:10px;">Crear Producto</span>
                </a>
                @endif

                {{-- ENTRADAS Y SALIDAS: Lo ven Dueño, Administrador y Encargado --}}
                @if(in_array($role, ['dueno', 'encargado', 'administrador']))
                <a href="{{ route('almacen.entradas') }}" class="nav-item {{ request()->routeIs('almacen.entradas*') ? 'active' : '' }}" style="display:flex; padding:12px 20px; align-items:center;">
                    <svg width="20" height="20" class="w-5 h-5 text-white" fill="none" stroke="#FFFFFF" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v12m8-6l-4 4-4-4"/></svg>
                    <span class="ml-2" style="color:#FFFFFF; margin-left:10px;">Entradas</span>
                </a>

                <a href="{{ route('almacen.salidas') }}" class="nav-item {{ request()->routeIs('almacen.salidas*') ? 'active' : '' }}" style="display:flex; padding:12px 20px; align-items:center;">
                    <svg width="20" height="20" class="w-5 h-5 text-white" fill="none" stroke="#FFFFFF" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22V10m-8 6l4-4 4 4"/></svg>
                    <span class="ml-2" style="color:#FFFFFF; margin-left:10px;">Salidas</span>
                </a>
                @endif

                {{-- VENTAS: Solo lo ve el Dueño, Administrador o Cajero --}}
                @if(in_array($role, ['dueno', 'administrador']))
                <a href="{{ route('admin.ventas.index') }}" class="nav-item {{ request()->routeIs('admin.ventas.*') ? 'active' : '' }}" style="display:flex; padding:12px 20px; align-items:center;">
                    <svg width="20" height="20" class="w-5 h-5 text-white" fill="none" stroke="#FFFFFF" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v4H3zM3 7v11a2 2 0 002 2h14a2 2 0 002-2V7"/></svg>
                    <span class="ml-2" style="color:#FFFFFF; margin-left:10px;">Ventas</span>
                </a>
                @elseif($role === 'cajero')
                <a href="{{ route('cajero.ventas.index') }}" class="nav-item {{ request()->routeIs('cajero.ventas.*') ? 'active' : '' }}" style="display:flex; padding:12px 20px; align-items:center;">
                    <svg width="20" height="20" class="w-5 h-5 text-white" fill="none" stroke="#FFFFFF" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v4H3zM3 7v11a2 2 0 002 2h14a2 2 0 002-2V7"/></svg>
                    <span class="ml-2" style="color:#FFFFFF; margin-left:10px;">Ventas</span>
                </a>
                @endif

                {{-- PROVEEDORES, USUARIOS, REPORTES: Solo Dueño o Administrador --}}
                @if(in_array($role, ['dueno', 'administrador']))
                <a href="{{ route('admin.proveedores.index') }}" class="nav-item {{ request()->routeIs('admin.proveedores.*') ? 'active' : '' }}" style="display:flex; padding:12px 20px; align-items:center;">
                    <svg width="20" height="20" class="w-5 h-5 text-white" fill="none" stroke="#FFFFFF" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0zM13 16V8a1 1 0 00-1-1H3v9h1a1 1 0 011 1v1h12v-2a1 1 0 011-1h1V11l-4-4h-3z"/></svg>
                    <span class="ml-2" style="color:#FFFFFF; margin-left:10px;">Proveedores</span>
                </a>

                <a href="{{ route('admin.usuarios.index') }}" class="nav-item {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}" style="display:flex; padding:12px 20px; align-items:center;">
                    <svg width="20" height="20" class="w-5 h-5 text-white" fill="none" stroke="#FFFFFF" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87"/></svg>
                    <span class="ml-2" style="color:#FFFFFF; margin-left:10px;">Usuarios</span>
                </a>

                <a href="{{ route('admin.reportes.index') }}" class="nav-item {{ request()->routeIs('admin.reportes.*') ? 'active' : '' }}" style="display:flex; padding:12px 20px; align-items:center;">
                    <svg width="20" height="20" class="w-5 h-5 text-white" fill="none" stroke="#FFFFFF" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20V9M12 20V4M17 20v-6"/></svg>
                    <span class="ml-2" style="color:#FFFFFF; margin-left:10px;">Reportes</span>
                </a>
                @endif

                {{-- CERRAR SESIÓN: Aplica para todos --}}
                <div style="padding-top: 20px;">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full nav-item logout-btn" style="display:flex; padding:12px 20px; align-items:center; background:none; border:none; cursor:pointer; width:100%; text-align:left;">
                            <svg width="20" height="20" class="w-5 h-5 mr-2 text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16 17l5-5-5-5" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M21 12H9" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M13 19H6a2 2 0 01-2-2V7a2 2 0 012-2h7" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span class="ml-2" style="color:#FFFFFF; margin-left:10px;">Cerrar sesión</span>
                        </button>
                    </form>
                </div>

            @endauth
            </nav>
        </aside>

        {{-- 2. CONTENIDO PRINCIPAL (DERECHA) --}}
        <main class="main-content flex-1" style="margin-left: 250px; width: calc(100% - 250px); min-height: 100vh; transition: all 0.3s ease;">
            
            {{-- BARRA BLANCA SUPERIOR (TOPBAR) --}}
           <header class="floating-topbar" style="position: fixed; top: 0; right: 0; width: calc(100% - 250px); height: 70px; background-color: #FFFFFF; z-index: 900; display: flex; align-items: center; justify-content: space-between; padding: 0 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); transition: all 0.3s ease;">
    
   <!-- LADO IZQUIERDO: BOTÓN CON TEXTO FUNDIDO Y CON MARGEN -->
    <div style="display: flex; align-items: center; z-index: 10000; margin-left: 60px;">
        <button id="botonMagicoMenu" style="background-color: transparent; border: none; border-radius: 8px; cursor: pointer; padding: 8px 12px; display: flex; align-items: center; gap: 8px; color: #1E3A8A; font-weight: 700; font-size: 14px; transition: all 0.2s; box-shadow: none;" onmouseover="this.style.backgroundColor='#F1F5F9'" onmouseout="this.style.backgroundColor='transparent'">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#1E3A8A" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
            
        </button>
    </div>

    <!-- LADO DERECHO: USUARIO -->
    <div class="topbar-right">
        @auth
            <div style="display:flex;align-items:center;gap:15px;color:#111827;font-weight:600;">
                <div>{{ ucfirst(Auth::user()->rol ?? 'Dueño') }} | {{ Auth::user()->name ?? 'Nombre' }}</div>
                <div style="width:36px;height:36px;border-radius:50%;background:#1E3A8A;display:flex;align-items:center;justify-content:center;color:#FFFFFF;font-weight:700;">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U',0,1)) }}
                </div>
            </div>
        @endauth
    </div>
</header>
            {{-- ZONA DE VISTAS (AQUÍ CARGA EL DASHBOARD, PRODUCTOS, ETC) --}}
            <div style="padding-top: 100px; padding-left: 30px; padding-right: 30px; padding-bottom: 30px;">
                @if(session('status'))
                    <div class="mb-4 p-3 rounded" style="background-color: #ECFDF5; color: #065F46; border: 1px solid #34D399;">
                        {{ session('status') }}
                    </div>
                @endif

                @yield('content')
            </div>

        </main>
    </div>

    {{-- SCRIPT LIMPIO PARA COLAPSAR --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnToggle = document.getElementById('botonMagicoMenu');
        if (btnToggle) {
            btnToggle.addEventListener('click', function() {
                const sidebar = document.getElementById('sidebar');
                const mainContent = document.querySelector('.main-content');
                const topbar = document.querySelector('.floating-topbar');

                if (sidebar.classList.contains('sidebar-collapsed')) {
                    // Expandir
                    sidebar.classList.remove('sidebar-collapsed');
                    sidebar.style.width = '250px';
                    mainContent.style.marginLeft = '250px';
                    mainContent.style.width = 'calc(100% - 250px)';
                    topbar.style.width = 'calc(100% - 250px)';
                    
                    document.querySelectorAll('.nav-item span, .brand-text').forEach(el => el.style.display = 'flex');
                } else {
                    // Colapsar
                    sidebar.classList.add('sidebar-collapsed');
                    sidebar.style.width = '80px';
                    mainContent.style.marginLeft = '80px';
                    mainContent.style.width = 'calc(100% - 80px)';
                    topbar.style.width = 'calc(100% - 80px)';
                    
                    document.querySelectorAll('.nav-item span, .brand-text').forEach(el => el.style.display = 'none');
                }
            });
        }
    });
</script>
</body>
</html>