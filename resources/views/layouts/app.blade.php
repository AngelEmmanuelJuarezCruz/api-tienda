<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Abarrotes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">

        {{-- SIDEBAR --}}
        <aside class="w-56 bg-white border-r flex flex-col p-4">

            <h1 class="text-lg font-bold text-indigo-600 mb-8">
                Tienda Abarrotes
            </h1>

            <nav class="flex flex-col gap-2 text-sm">

                @auth

                    @if(Auth::user()->rol === 'dueño')
                        <a href="/admin/dashboard"
                           class="px-3 py-2 rounded hover:bg-indigo-50 text-gray-700">
                           Panel General
                        </a>
                        <a href="/almacen/productos"
                           class="px-3 py-2 rounded hover:bg-indigo-50 text-gray-700">
                           Inventario
                        </a>
                        <a href="/ventas/dashboard"
                           class="px-3 py-2 rounded hover:bg-indigo-50 text-gray-700">
                           Ventas
                        </a>
                    @endif

                    @if(Auth::user()->rol === 'encargado')
                        <a href="/almacen/dashboard"
                           class="px-3 py-2 rounded hover:bg-indigo-50 text-gray-700">
                           Mi Panel
                        </a>
                        <a href="/almacen/productos"
                           class="px-3 py-2 rounded hover:bg-indigo-50 text-gray-700">
                           Inventario
                        </a>
                    @endif

                    @if(Auth::user()->rol === 'cajero')
                        <a href="/ventas/dashboard"
                           class="px-3 py-2 rounded hover:bg-indigo-50 text-gray-700">
                           Mi Panel
                        </a>
                    @endif

                @endauth

            </nav>

            {{-- BOTON CERRAR SESION --}}
            @auth
            <div class="mt-auto">
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit"
                        class="w-full text-left px-3 py-2 text-sm text-red-500 hover:bg-red-50 rounded">
                        Cerrar sesión
                    </button>
                </form>
            </div>
            @endauth

        </aside>

        {{-- CONTENIDO PRINCIPAL --}}
        <main class="flex-1 p-8">

            {{-- TOPBAR --}}
            @auth
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">
                    @yield('titulo', 'Panel')
                </h2>
                <span class="text-sm text-gray-500">
                    {{ Auth::user()->name }}
                    <span class="ml-2 bg-indigo-100 text-indigo-700 px-2 py-1 rounded text-xs font-medium">
                        {{ Auth::user()->rol }}
                    </span>
                </span>
            </div>
            @endauth

            @yield('content')

        </main>
    </div>

</body>
</html>