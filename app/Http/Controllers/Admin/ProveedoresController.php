<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProveedoresController extends Controller
{
    /**
     * Mostrar listado de proveedores.
     */
    public function index(Request $request)
    {
        return view('admin.proveedores.index');
    }
}
