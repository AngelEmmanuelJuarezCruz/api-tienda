<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;

class AlmacenController extends Controller
{
    public function index(Request $request)
    {
        $items = Producto::all();
        return view('admin.almacen.index', compact('items'));
    }
}