<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Insumos Médicos</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-[#F2F4F7] min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8">
        
        <header class="text-center mb-8">
            <div class="mx-auto w-16 h-16 bg-[#E6EEF8] text-[#1E3A8A] rounded-lg flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M13 11h6a1 1 0 010 2h-6v6a1 1 0 01-2 0v-6H5a1 1 0 010-2h6V5a1 1 0 012 0v6z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-[#1E3A8A] uppercase">PROVEEDORA DE</h1>
            <h1 class="text-2xl font-bold text-[#1E3A8A] uppercase">INSUMOS MÉDICOS</h1>
            <p class="text-sm text-gray-500 mt-2">Sistema Integral de Ventas y Gestión de Inventarios</p>
        </header>

        <form method="POST" action="{{ route('login.post') ?? route('login') }}" class="space-y-6">
            @csrf
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1E3A8A] focus:border-[#1E3A8A] outline-none transition-all">
                @error('email')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                <input id="password" type="password" name="password" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1E3A8A] focus:border-[#1E3A8A] outline-none transition-all">
                @error('password')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-[#1E3A8A] border-gray-300 rounded focus:ring-[#1E3A8A]">
                    <span class="ml-2 text-sm text-gray-600">Recordarme</span>
                </label>
                <a href="#" class="text-sm font-medium text-[#1E3A8A] hover:text-blue-800 hover:underline transition-colors">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>

            <button type="submit" class="w-full bg-[#1E3A8A] hover:bg-blue-900 text-white font-semibold py-3 px-4 rounded-lg shadow-md transition-all">
                Iniciar sesión
            </button>
        </form>
    </div>

</body>
</html>