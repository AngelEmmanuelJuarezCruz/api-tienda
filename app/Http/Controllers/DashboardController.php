<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\User;
use App\Models\Venta;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function admin()
    {
        $inicioMes = now()->startOfMonth();
        $finMes = now()->endOfMonth();
        $inicioHoy = now()->startOfDay();
        $finHoy = now()->endOfDay();

        $stats = [
            'usuarios' => User::count(),
            'productos' => Producto::count(),
            'categorias' => Categoria::count(),
            'proveedores' => Proveedor::count(),
            'ventas' => Venta::count(),
            'ventas_hoy' => (float) Venta::whereBetween('fecha', [$inicioHoy, $finHoy])->sum('total'),
            'ingresos_mes' => (float) Venta::whereBetween('fecha', [$inicioMes, $finMes])->sum('total'),
            'stock_bajo' => Producto::stockBajo()->count(),
            'activos' => Producto::activos()->count(),
        ];

        $ultimosProductos = Producto::query()
            ->with(['categoria', 'proveedor'])
            ->orderBy('updated_at', 'desc')
            ->limit(6)
            ->get();

        $ventasUltimosDias = collect(range(6, 0))->map(function (int $diasAtras) {
            $fecha = now()->subDays($diasAtras);
            $total = (float) Venta::whereBetween('fecha', [
                $fecha->copy()->startOfDay(),
                $fecha->copy()->endOfDay(),
            ])->sum('total');

            return [
                'dia' => ucfirst($fecha->locale('es')->isoFormat('ddd')),
                'total' => $total,
            ];
        });

        $maxVentas = max($ventasUltimosDias->max('total'), 1);

        $productosMasVendidos = DetalleVenta::query()
            ->select('producto_id', DB::raw('SUM(cantidad) as unidades'))
            ->with('producto')
            ->groupBy('producto_id')
            ->orderByDesc('unidades')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'ultimosProductos',
            'ventasUltimosDias',
            'maxVentas',
            'productosMasVendidos'
        ));
    }

    public function almacen()
    {
        $stats = [
            'productos' => Producto::activos()->count(),
            'stock_bajo' => Producto::activos()->stockBajo()->count(),
            'proximos_caducar' => Producto::activos()->proximosACaducar()->count(),
            'categorias' => Categoria::count(),
        ];

        $productos = Producto::activos()
            ->with(['categoria', 'proveedor'])
            ->orderBy('stock_actual', 'asc')
            ->limit(10)
            ->get();

        return view('almacen.dashboard', compact('stats', 'productos'));
    }

    public function productos()
    {
        $productos = Producto::query()
            ->with(['categoria', 'proveedor'])
            ->orderBy('stock_actual', 'asc')
            ->get();

        return view('almacen.productos.index', compact('productos'));
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
