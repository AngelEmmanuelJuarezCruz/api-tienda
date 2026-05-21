@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="page-title">Gestion de usuarios y roles</h1>
            <p class="muted text-sm">El dueno puede crear usuarios nuevos y asignar roles.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert-error">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 panel-white">
            <h2 class="text-lg font-semibold mb-4">Crear usuario</h2>
            <form action="{{ route('admin.usuarios.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="form-label" for="nombre">Nombre completo</label>
                    <input id="nombre" name="name" type="text" value="{{ old('name') }}" required>
                </div>
                <div>
                    <label class="form-label" for="correo">Correo</label>
                    <input id="correo" name="email" type="email" value="{{ old('email') }}" required>
                </div>
                <div>
                    <label class="form-label" for="rol">Rol</label>
                    <div class="grid grid-cols-1 gap-2" role="radiogroup" aria-label="Rol del usuario">
                        @foreach([
                            'administrador' => 'Administrador',
                            'encargado' => 'Encargado',
                            'cajero' => 'Cajero',
                        ] as $value => $label)
                            <label class="role-option">
                                <input type="radio" name="rol" value="{{ $value }}" class="sr-only" required {{ old('rol') === $value ? 'checked' : '' }}>
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="form-label" for="password">Contrasena temporal</label>
                    <input id="password" name="password" type="password" required>
                </div>
                <label class="flex items-center gap-2 text-sm font-semibold text-slate-600">
                    <input type="checkbox" name="activo" value="1" {{ old('activo', true) ? 'checked' : '' }}>
                    Usuario activo
                </label>
                <button type="submit" class="btn btn-primary w-full justify-center">Crear usuario</button>
            </form>
        </div>

        <div class="lg:col-span-2 panel-white">
            <form method="GET" class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold">Usuarios registrados</h2>
                    <p class="muted text-xs">Resultados actuales: {{ $usuarios->count() }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Buscar por nombre o correo...">
                    <button type="submit" class="btn btn-outline">Buscar</button>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usuarios as $usuario)
                            <tr>
                                <td class="font-semibold text-slate-800">{{ $usuario->name }}</td>
                                <td class="text-slate-600">{{ $usuario->email }}</td>
                                <td class="text-slate-600">{{ ucfirst($usuario->rol ?? 'usuario') }}</td>
                                <td>
                                    <span class="badge" style="background: {{ $usuario->activo ? 'rgba(15,118,110,0.12)' : 'rgba(15,23,42,0.08)' }}; color: {{ $usuario->activo ? 'var(--brand)' : 'var(--ink-soft)' }};">
                                        {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-sm muted py-6">No hay usuarios para mostrar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<style>
.role-option {
    display: flex;
    align-items: center;
    min-height: 42px;
    border: 1px solid rgba(15, 23, 42, 0.14);
    border-radius: 12px;
    background: #fff;
    padding: 10px 12px;
    font-size: 14px;
    font-weight: 600;
    color: #334155;
    cursor: pointer;
    transition: border-color .2s ease, box-shadow .2s ease, background .2s ease, color .2s ease;
}

.role-option:hover {
    border-color: rgba(30, 58, 138, 0.35);
    background: rgba(30, 58, 138, 0.04);
}

.role-option:has(input:checked) {
    border-color: #1E3A8A;
    background: #1E3A8A;
    color: #fff;
    box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.14);
}
</style>
@endsection
