<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Iniciar sesión — PROVEEDORA DE INSUMOS MÉDICOS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body{font-family:'Poppins',sans-serif;background-color:#F2F4F7}</style>
</head>
<body class="min-h-screen flex items-center justify-center" style="background-color:#F2F4F7;">
    <main class="w-full max-w-md p-6">
        <section class="bg-white shadow-xl rounded-xl p-6">
            <header class="mb-6 text-center">
                <div class="mx-auto mb-4 w-16 h-16 flex items-center justify-center rounded-lg" style="background-color:#E6EEF8;">
                    <!-- Medical cross icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="#1E3A8A" aria-hidden="true">
                        <path d="M13 11h6a1 1 0 010 2h-6v6a1 1 0 01-2 0v-6H5a1 1 0 010-2h6V5a1 1 0 012 0v6z" />
                    </svg>
                </div>
                <h1 class="text-xl md:text-2xl font-bold text-[#1E3A8A] uppercase">PROVEEDORA DE INSUMOS MÉDICOS</h1>
                <p class="text-sm text-gray-500">Sistema Integral de Ventas y Gestión de Inventarios</p>
            </header>
            
            <form id="loginForm" method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                {{-- Email --}}
                @php $hasEmailError = $errors->has('email'); @endphp
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        required
                        autofocus
                        autocomplete="email"
                        inputmode="email"
                        value="{{ old('email') }}"
                        aria-invalid="{{ $hasEmailError ? 'true' : 'false' }}"
                        aria-describedby="{{ $hasEmailError ? 'email-error' : '' }}"
                        class="mt-1 block w-full px-3 py-2 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] 
                        {{ $hasEmailError ? 'border-red-500 ring-1 ring-red-500' : 'border border-gray-300' }}"
                    />
                    @error('email')
                        <p id="email-error" class="mt-2 text-sm text-red-600 flex items-start gap-2" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M8.257 3.099c.366-.764 1.42-.764 1.786 0l6.518 13.6A1 1 0 0115.81 18H4.19a1 1 0 01-.751-1.301l4.818-13.6zM11 14a1 1 0 10-2 0 1 1 0 002 0zm-1-8a1 1 0 00-.993.883L8.82 9h2.36l-.186-2.117A1 1 0 0010 6z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                {{-- Password --}}
                @php $hasPasswordError = $errors->has('password'); @endphp
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <div class="mt-1 relative">
                        <input
                            id="password"
                            name="password"
                            type="password"
                                required
                                autocomplete="current-password"
                            aria-invalid="{{ $hasPasswordError ? 'true' : 'false' }}"
                            aria-describedby="{{ $hasPasswordError ? 'password-error' : '' }}"
                            class="block w-full px-3 py-2 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1E3A8A]
                            {{ $hasPasswordError ? 'border-red-500 ring-1 ring-red-500' : 'border border-gray-300' }}"
                        />

                        <button
                            type="button"
                            id="togglePassword"
                            aria-pressed="false"
                            aria-label="Mostrar contraseña"
                            class="absolute inset-y-0 right-1 flex items-center px-2 text-gray-500 hover:text-gray-700 focus:outline-none"
                        >
                            <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 3C6 3 2.73 5.11 1 8.5 2.73 11.89 6 14 10 14s7.27-2.11 9-5.5C17.27 5.11 14 3 10 3zM10 12a3 3 0 110-6 3 3 0 010 6z" />
                            </svg>
                            <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M4.03 3.97a.75.75 0 10-1.06 1.06l2.1 2.1A9.01 9.01 0 001 8.5C2.73 11.89 6 14 10 14c1.6 0 3.1-.32 4.43-.88l2.54 2.54a.75.75 0 101.06-1.06L4.03 3.97zM7.1 8.36a3 3 0 104.54 4.54L7.1 8.36z" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p id="password-error" class="mt-2 text-sm text-red-600 flex items-start gap-2" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M8.257 3.099c.366-.764 1.42-.764 1.786 0l6.518 13.6A1 1 0 0115.81 18H4.19a1 1 0 01-.751-1.301l4.818-13.6zM11 14a1 1 0 10-2 0 1 1 0 002 0zm-1-8a1 1 0 00-.993.883L8.82 9h2.36l-.186-2.117A1 1 0 0010 6z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 accent-[#108981] border-gray-300 rounded focus:ring-[#108981]" />
                        <label for="remember" class="ml-2 block text-sm text-gray-600">Recordarme</label>
                    </div>
                    <div>
                        <a href="#" class="text-sm text-[#1E3A8A] hover:underline">¿Olvidaste tu contraseña?</a>
                    </div>
                </div>

                <div>
                    <button
                        id="submitBtn"
                        type="submit"
                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-[#1E3A8A] hover:bg-[#162f66] text-white font-medium rounded-md focus:outline-none disabled:opacity-50"
                    >
                        <svg id="btnSpinner" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        <span id="btnText">Iniciar Sesión</span>
                    </button>
                </div>
            </form>
        </section>
    </main>

    <script>
        (function () {
            const toggle = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClosed = document.getElementById('eyeClosed');
            const form = document.getElementById('loginForm');
            const submitBtn = document.getElementById('submitBtn');
            const btnSpinner = document.getElementById('btnSpinner');
            const btnText = document.getElementById('btnText');

            if (toggle && password) {
                toggle.addEventListener('click', function () {
                    const isPassword = password.type === 'password';
                    password.type = isPassword ? 'text' : 'password';
                    toggle.setAttribute('aria-pressed', String(isPassword));
                    toggle.setAttribute('aria-label', isPassword ? 'Ocultar contraseña' : 'Mostrar contraseña');
                    eyeOpen.classList.toggle('hidden');
                    eyeClosed.classList.toggle('hidden');
                });

                // Remove error styling on input to provide instant feedback
                password.addEventListener('input', function () {
                    password.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
                    const err = document.getElementById('password-error');
                    if (err) err.style.display = 'none';
                });
            }

            const email = document.getElementById('email');
            if (email) {
                email.addEventListener('input', function () {
                    email.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
                    const err = document.getElementById('email-error');
                    if (err) err.style.display = 'none';
                });
            }

            if (form) {
                form.addEventListener('submit', function (e) {
                    // Prevent double submit UX — allow server to handle validation
                    submitBtn.disabled = true;
                    btnSpinner.classList.remove('hidden');
                    btnText.textContent = 'Cargando...';
                });
            }
        })();
    </script>
</body>
</html>
