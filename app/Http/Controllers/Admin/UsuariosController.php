<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    /**
     * Mostrar listado de usuarios.
     */
    public function index(Request $request)
    {
        return view('admin.usuarios.index');
    }
}
