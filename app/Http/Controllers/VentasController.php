<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VentasController extends Controller
{
    /**
     * Mostrar el simulador de Punto de Venta.
     */
    public function index(Request $request)
    {
        return view('admin.ventas.index');
    }
}
