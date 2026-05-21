<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuariosController extends Controller
{
    /**
     * Mostrar listado de usuarios.
     */
    public function index(Request $request)
    {
        $query = User::query()->orderBy('name');

        if ($request->filled('q')) {
            $search = $request->string('q')->toString();
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $usuarios = $query->get();

        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Crear un usuario nuevo.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'rol' => ['required', 'string', Rule::in(['administrador', 'encargado', 'cajero'])],
            'password' => ['required', 'string', 'min:8'],
            'activo' => ['nullable', 'boolean'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'rol' => $data['rol'],
            'password' => Hash::make($data['password']),
            'activo' => $request->boolean('activo'),
        ]);

        return redirect()->route('admin.usuarios.index')->with('status', 'Usuario creado correctamente.');
    }
}
