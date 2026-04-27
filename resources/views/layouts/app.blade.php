<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Insumos Médicos</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Sidebar (fixed column) */
        #sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 16rem; /* 64 */
            background: #1E3A8A;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            transition: all 0.3s ease;
            z-index: 30;
        }

        /* Main content is pushed by the sidebar width */
        .main-content { margin-left: 16rem; transition: all 0.3s ease; }

        /* Collapsed sidebar: narrow and keep content margin in sync (w-20 = 5rem) */
        .sidebar-collapsed #sidebar { width: 5rem; }
        .sidebar-collapsed .main-content { margin-left: 5rem; }

        /* Nav spacing and styles */
        #sidebar nav { display: flex; flex-direction: column; }
        #sidebar .nav-items { display: flex; flex-direction: column; gap: 1.5rem; } /* space-y-6 equivalent */
        #sidebar .nav-item { color: rgba(255,255,255,0.95); display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem; transition: background 0.18s ease; }
        #sidebar .nav-item svg { color: rgba(255,255,255,0.95); }
        #sidebar .nav-item:hover { background: rgba(255,255,255,0.06); border-radius: 0.5rem; }
        #sidebar .nav-item.active { background: rgba(255,255,255,0.12); border-radius: 9999px; }

        /* When collapsed hide labels to keep compact */
        .sidebar-collapsed #sidebar h1 { display: none; }
        .sidebar-collapsed #sidebar .nav-item span { display: none; }

        /* Center icons and increase size when collapsed */
        .sidebar-collapsed #sidebar .nav-item { justify-content: center; }
        .sidebar-collapsed #sidebar .nav-item svg { width: 1.5rem; height: 1.5rem; }
        /* Keep vertical spacing when collapsed */
        .sidebar-collapsed #sidebar .nav-items { gap: 1.5rem; }

        /* Username and small UI tweaks */
        .user-badge { color: #1E3A8A; }

        /* Topbar look */
        .floating-topbar {
            background: #F2F4F7;
            border-radius: 9999px;
            padding: 0.5rem 1rem;
            box-shadow: 0 8px 20px rgba(2,6,23,0.06);
        }

        @media (max-width: 767px) {
            #sidebar { position: relative; width: 100%; height: auto; }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body style="background: #F2F4F7; font-family: 'Poppins', sans-serif;">

    <div class="flex min-h-screen">

        {{-- (old sidebar removed; using floating panel) --}}

        {{-- CONTENIDO PRINCIPAL --}}
        <main class="main-content flex-1 p-4 md:p-8">

            {{-- TOPBAR --}}
            <div class="flex items-center justify-between mb-6 floating-topbar">
                <div class="flex items-center gap-4">
                    <button id="sidebar-toggle" onclick="toggleSidebar()" class="p-2 rounded-md bg-white/90 text-[#1E3A8A] focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    @auth
                        @php $topRole = strtolower(Auth::user()->rol ?? ''); @endphp
                        @if($topRole === 'cajero')
                            <a href="{{ route('cajero.dashboard') }}" class="text-xl font-bold text-gray-800" style="font-family: 'Poppins', sans-serif;">Gestión de Insumos Médicos</a>
                        @else
                            <a href="{{ route('admin.index') }}" class="text-xl font-bold text-gray-800" style="font-family: 'Poppins', sans-serif;">Gestión de Insumos Médicos</a>
                        @endif
                    @else
                        <a href="{{ route('admin.index') }}" class="text-xl font-bold text-gray-800" style="font-family: 'Poppins', sans-serif;">Gestión de Insumos Médicos</a>
                    @endauth
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        <div class="relative flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-white flex items-center justify-center text-sm font-semibold text-[#1E3A8A]">{{ strtoupper(substr(Auth::user()->name,0,1)) }}</div>
                            <div class="hidden md:flex md:flex-col md:items-start">
                                <span class="text-sm text-gray-800 font-medium">{{ Auth::user()->name }}</span>
                                <span class="text-xs text-[#1E3A8A]">{{ Auth::user()->rol }}</span>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>

            {{-- SIDEBAR (fixed, pushes content) --}}
            <aside id="sidebar">
                <a href="{{ route('admin.index') }}" class="flex items-center gap-3 mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7l9-4 9 4v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    <h1 class="text-lg font-bold text-white" style="font-family: 'Poppins', sans-serif;">Gestión de Insumos Médicos</h1>
                </a>

                <nav class="nav-items">
                    @auth
                        @php $role = strtolower(Auth::user()->rol ?? ''); @endphp

                        @if($role === 'cajero')
                        <a href="{{ route('cajero.dashboard') }}" class="nav-item {{ request()->routeIs('cajero.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6"/></svg>
                            <span class="ml-2 text-white">Panel</span>
                        </a>
                        @else
                        <a href="{{ route('admin.index') }}" class="nav-item {{ request()->routeIs('admin.index') ? 'active' : '' }}">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6"/></svg>
                            <span class="ml-2 text-white">Panel</span>
                        </a>
                        @endif

                        @if(in_array($role, ['dueno','encargado','administrador']))
                        <a href="{{ route('admin.almacen.index') }}" class="nav-item {{ request()->routeIs('admin.almacen.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 7l3 13h12l3-13"/></svg>
                            <span class="ml-2 text-white">Almacén</span>
                        </a>
                        @endif

                        @if(in_array($role, ['dueno','administrador']))
                        <a href="{{ route('admin.ventas.index') }}" class="nav-item {{ request()->routeIs('admin.ventas.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v4H3zM3 7v11a2 2 0 002 2h14a2 2 0 002-2V7"/></svg>
                            <span class="ml-2 text-white">Ventas</span>
                        </a>
                        @elseif($role === 'cajero')
                        <a href="{{ route('cajero.ventas.index') }}" class="nav-item {{ request()->routeIs('cajero.ventas.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v4H3zM3 7v11a2 2 0 002 2h14a2 2 0 002-2V7"/></svg>
                            <span class="ml-2 text-white">Ventas</span>
                        </a>
                        @endif

                        @if(in_array($role, ['dueno','administrador']))
                        <a href="{{ route('admin.proveedores.index') }}" class="nav-item {{ request()->routeIs('admin.proveedores.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0zM13 16V8a1 1 0 00-1-1H3v9h1a1 1 0 011 1v1h12v-2a1 1 0 011-1h1V11l-4-4h-3z"/></svg>
                            <span class="ml-2 text-white">Proveedores</span>
                        </a>

                        <a href="{{ route('admin.usuarios.index') }}" class="nav-item {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87"/></svg>
                            <span class="ml-2 text-white">Usuarios</span>
                        </a>

                        <a href="{{ route('admin.reportes.index') }}" class="nav-item {{ request()->routeIs('admin.reportes.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20V9M12 20V4M17 20v-6"/></svg>
                            <span class="ml-2 text-white">Reportes</span>
                        </a>
                        @endif

                        {{-- logout --}}
                        <div class="mt-auto">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full nav-item text-left text-white hover:bg-white/10">
                                    <svg class="w-5 h-5 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/></svg>
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