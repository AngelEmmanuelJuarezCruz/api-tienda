<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;

class AlmacenController extends Controller
{
    public function index(Request $request)
    {
        $productos = Producto::with(['categoria', 'bitacoraMovimientosStock'])->get();
        return view('admin.almacen.index', compact('productos'));
    }
}