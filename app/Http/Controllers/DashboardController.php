<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function admin()
    {
        $stats = [
            'usuarios' => User::count(),
            'productos' => Producto::count(),
            'categorias' => Categoria::count(),
            'proveedores' => Proveedor::count(),
            'ventas' => Venta::count(),
            'stock_bajo' => Producto::stockBajo()->count(),
            'activos' => Producto::activos()->count(),
        ];

        $ultimosProductos = Producto::query()
            ->with(['categoria', 'proveedor'])
            ->orderBy('updated_at', 'desc')
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact('stats', 'ultimosProductos'));
    }

    public function almacen()
    {
        $stats = [
            'productos' => Producto::count(),
            'stock_bajo' => Producto::stockBajo()->count(),
            'proximos_caducar' => Producto::proximosACaducar()->count(),
            'categorias' => Categoria::count(),
        ];

        $productos = Producto::query()
            ->with(['categoria', 'proveedor'])
            ->orderBy('stock_actual', 'asc')
            ->limit(10)
            ->get();

        return view('almacen.dashboard', compact('stats', 'productos'));
    }

    public function productos(Request $request)
    {
        $categorias = Categoria::orderBy('nombre')->get();
        $proveedores = Proveedor::orderBy('nombre')->get();

        $productos = Producto::query()
            ->with(['categoria', 'proveedor', 'lotesProducto']);

        if ($request->filled('search')) {
            $productos->where(function ($query) use ($request) {
                $query->where('nombre', 'like', '%'.$request->search.'%')
                      ->orWhere('sku', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('categoria')) {
            $productos->porCategoria($request->categoria);
        }

        if ($request->filled('proveedor')) {
            $productos->porProveedor($request->proveedor);
        }

        if ($request->filled('caducidad')) {
            if ($request->caducidad === 'prox7') {
                $productos->proximosACaducar(7);
            } elseif ($request->caducidad === 'conCaducidad') {
                $productos->whereHas('lotesProducto', function ($query) {
                    $query->whereNotNull('fecha_caducidad');
                });
            }
        }

        $productos = $productos->orderBy('stock_actual', 'asc')->get();

        return view('almacen.productos.index', compact('productos', 'categorias', 'proveedores'));
    }

    public function alertas(Request $request)
    {
        $productos = Producto::query()
            ->with(['categoria', 'proveedor', 'lotesProducto'])
            ->where(function ($query) {
                $query->whereColumn('stock_actual', '<', 'stock_minimo')
                    ->orWhereHas('lotesProducto', function ($sub) {
                        $sub->whereNotNull('fecha_caducidad')
                            ->whereBetween('fecha_caducidad', [now(), now()->addDays(7)]);
                    });
            })
            ->orderByRaw('stock_actual < stock_minimo desc')
            ->orderBy('stock_actual', 'asc')
            ->get();

        return view('almacen.alertas', compact('productos'));
    }

    public function ventas()
    {
        $ventas = Venta::query()
            ->with('usuario')
            ->orderBy('fecha', 'desc')
            ->limit(10)
            ->get();

        $stats = [
            'ventas' => Venta::count(),
            'total' => (float) Venta::sum('total'),
            'usuarios' => User::count(),
        ];

        return view('ventas.dashboard', compact('ventas', 'stats'));
    }
}