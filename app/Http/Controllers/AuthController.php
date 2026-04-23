<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Procesa la solicitud de inicio de sesión.
     */
    public function login(Request $request)
    {
        // Validamos correo y contraseña
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Autenticamos contra la base de datos
        if (Auth::attempt($credentials)) {
            // Protección contra fijación de sesión
            $request->session()->regenerate();
            
            $user = Auth::user();

            // Redirección post-login según el rol
            if (in_array($user->rol, ['dueño', 'administrador'])) {
                return redirect()->intended('/admin');
            } elseif ($user->rol === 'encargado') {
                return redirect()->intended('/almacen');
            } elseif ($user->rol === 'cajero') {
                return redirect()->intended('/ventas');
            }

            return redirect()->intended('/');
        }

        // Si falla, regresamos atrás con error
        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidar sesión y regenerar token CSRF para seguridad
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
