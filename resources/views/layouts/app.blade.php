<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Insumos Médicos</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    <div class="flex min-h-screen">

        {{-- (old sidebar removed; using floating panel) --}}

        {{-- CONTENIDO PRINCIPAL --}}
        <main class="main-content flex-1 p-4 md:p-8">

            {{-- TOPBAR --}}
            <div class="floating-topbar">
                <div style="display:flex;align-items:center;gap:16px;">
                    <button id="sidebar-toggle" aria-label="Toggle sidebar" aria-expanded="true" onclick="toggleSidebar()" class="hamburger-btn" style="background:transparent;border:none;cursor:pointer;padding:6px;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#111827" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    @auth
                        @php $topRole = strtolower(Auth::user()->rol ?? ''); @endphp
                        @if($topRole === 'cajero')
                            <div class="text-xl font-semibold" style="color:#1E3A8A;">Gestión de Insumos Médicos</div>
                        @else
                            <div class="text-xl font-semibold" style="color:#1E3A8A;">Gestión de Insumos Médicos</div>
                        @endif
                    @else
                        <div class="text-xl font-semibold" style="color:#1E3A8A;">Gestión de Insumos Médicos</div>
                    @endauth
                </div>

                <div style="display:flex;align-items:center;gap:12px;">
                    @auth
                        <div style="display:flex;align-items:center;gap:8px;color:#111827;font-weight:600;">
                            <div>{{ ucfirst(Auth::user()->rol ?? 'Dueño') }} | {{ Auth::user()->name ?? 'Nombre' }}</div>
                            <div style="width:36px;height:36px;border-radius:9999px;background:#E6EEF8;display:flex;align-items:center;justify-content:center;color:#1E3A8A;font-weight:700;">
                                {{ strtoupper(substr(Auth::user()->name ?? 'U',0,1)) }}
                            </div>
                        </div>
                    @endauth
                </div>
            </div>

            {{-- SIDEBAR (fixed, pushes content) --}}
            <aside id="sidebar">
                        <a href="{{ route('admin.index') }}" class="sidebar-brand flex items-center gap-3 mb-6">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="flex:0 0 auto;">
                                <rect x="10" y="3" width="4" height="18" rx="1" fill="#FFFFFF"/>
                                <rect x="3" y="10" width="18" height="4" rx="1" fill="#FFFFFF"/>
                            </svg>
                            <div class="brand-text" style="line-height:1;">
                                <div style="font-size:12px;font-weight:700;color:#ffffff;text-transform:uppercase;">PROVEEDORA DE</div>
                                <div style="font-size:12px;font-weight:700;color:#ffffff;text-transform:uppercase;">INSUMOS MÉDICOS</div>
                            </div>
                        </a>

                <nav class="nav-items">
                    @auth
                        @php $role = strtolower(Auth::user()->rol ?? ''); @endphp

                        @if($role === 'cajero')
                        <a href="{{ route('cajero.dashboard') }}" class="nav-item {{ request()->routeIs('cajero.*') ? 'active' : '' }}">
                            <svg width="20" height="20" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6"/></svg>
                            <span class="ml-2 text-white">Panel</span>
                        </a>
                        @else
                        <a href="{{ route('admin.index') }}" class="nav-item {{ request()->routeIs('admin.index') ? 'active' : '' }}">
                            <svg width="20" height="20" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6"/></svg>
                            <span class="ml-2 text-white">Panel</span>
                        </a>
                        @endif

                        @if(in_array($role, ['dueno','encargado','administrador']))
                        <a href="{{ route('admin.almacen.index') }}" class="nav-item {{ request()->routeIs('admin.almacen.*') ? 'active' : '' }}">
                            <svg width="20" height="20" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 7l3 13h12l3-13"/></svg>
                            <span class="ml-2 text-white">Almacén</span>
                        </a>
                        @endif

                        {{-- Productos - visible para dueño/encargado/administrador --}}
                        @if(in_array($role, ['dueno','encargado','administrador']))
                        <a href="{{ route('almacen.productos') }}" class="nav-item {{ request()->routeIs('almacen.productos*') ? 'active' : '' }}">
                            <svg width="20" height="20" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18v11a2 2 0 01-2 2H5a2 2 0 01-2-2zM16 3v4M8 3v4"/></svg>
                            <span class="ml-2 text-white">Productos</span>
                        </a>
                        @endif
                            <a href="{{ route('almacen.productos.create') }}" class="nav-item {{ request()->routeIs('almacen.productos.create') ? 'active' : '' }}">
                                <svg width="20" height="20" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span class="ml-2 text-white">Crear Producto</span>
                            </a>

                        {{-- Entradas y Salidas - sólo dueño y encargado --}}
                        @if(in_array($role, ['dueno','encargado']))
                        <a href="{{ route('almacen.entradas') }}" class="nav-item {{ request()->routeIs('almacen.entradas*') ? 'active' : '' }}">
                            <svg width="20" height="20" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v12m8-6l-4 4-4-4"/></svg>
                            <span class="ml-2 text-white">Entradas</span>
                        </a>

                        <a href="{{ route('almacen.salidas') }}" class="nav-item {{ request()->routeIs('almacen.salidas*') ? 'active' : '' }}">
                            <svg width="20" height="20" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22V10m-8 6l4-4 4 4"/></svg>
                            <span class="ml-2 text-white">Salidas</span>
                        </a>
                        @endif

                        @if(in_array($role, ['dueno','administrador']))
                        <a href="{{ route('admin.ventas.index') }}" class="nav-item {{ request()->routeIs('admin.ventas.*') ? 'active' : '' }}">
                            <svg width="20" height="20" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v4H3zM3 7v11a2 2 0 002 2h14a2 2 0 002-2V7"/></svg>
                            <span class="ml-2 text-white">Ventas</span>
                        </a>
                        @elseif($role === 'cajero')
                        <a href="{{ route('cajero.ventas.index') }}" class="nav-item {{ request()->routeIs('cajero.ventas.*') ? 'active' : '' }}">
                            <svg width="20" height="20" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v4H3zM3 7v11a2 2 0 002 2h14a2 2 0 002-2V7"/></svg>
                            <span class="ml-2 text-white">Ventas</span>
                        </a>
                        @endif

                        @if(in_array($role, ['dueno','administrador']))
                        <a href="{{ route('admin.proveedores.index') }}" class="nav-item {{ request()->routeIs('admin.proveedores.*') ? 'active' : '' }}">
                            <svg width="20" height="20" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0zM13 16V8a1 1 0 00-1-1H3v9h1a1 1 0 011 1v1h12v-2a1 1 0 011-1h1V11l-4-4h-3z"/></svg>
                            <span class="ml-2 text-white">Proveedores</span>
                        </a>

                        <a href="{{ route('admin.usuarios.index') }}" class="nav-item {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">
                            <svg width="20" height="20" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87"/></svg>
                            <span class="ml-2 text-white">Usuarios</span>
                        </a>

                        <a href="{{ route('admin.reportes.index') }}" class="nav-item {{ request()->routeIs('admin.reportes.*') ? 'active' : '' }}">
                            <svg width="20" height="20" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20V9M12 20V4M17 20v-6"/></svg>
                            <span class="ml-2 text-white">Reportes</span>
                        </a>
                        @endif

                        {{-- logout --}}
                        <div class="mt-auto">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full nav-item logout-btn" style="display:flex;align-items:center;">
                                    <svg width="20" height="20" class="w-5 h-5 mr-2 text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16 17l5-5-5-5" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M21 12H9" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M13 19H6a2 2 0 01-2-2V7a2 2 0 012-2h7" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <span class="ml-2">Cerrar sesión</span>
                                </button>
                            </form>
                        </div>

                    @endauth
                </nav>
            </aside>

            {{-- PAGE CONTENT --}}
            <div class="w-full mt-6">
                @if(session('status'))
                    <div class="mb-4 p-3 rounded bg-green-50 text-green-800">{{ session('status') }}</div>
                @endif

                @yield('content')
            </div>

        </main>

    </div>

    <script>
        // Toggle sidebar expanded/collapsed (pushes content)
        function toggleSidebar() {
            document.documentElement.classList.toggle('sidebar-collapsed');
            var btn = document.getElementById('sidebar-toggle');
            if (btn) {
                var collapsed = document.documentElement.classList.contains('sidebar-collapsed');
                btn.setAttribute('aria-expanded', String(!collapsed));
            }
        }

        function toggleUserMenu() {
            var el = document.getElementById('user-menu'); if (!el) return; el.classList.toggle('hidden');
        }

        // Close user menu when clicking outside (sidebar is persistent/pushing, so do not auto-close)
        document.addEventListener('click', function(e){
            var menu = document.getElementById('user-menu'); var btn = document.getElementById('user-menu-button');
            if (menu && btn && !menu.classList.contains('hidden') && !menu.contains(e.target) && !btn.contains(e.target)) menu.classList.add('hidden');
        });
    </script>
</body>
</html>