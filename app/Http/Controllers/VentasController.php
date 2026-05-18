<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VentasController extends Controller
{
    /**
     * Mostrar el simulador de Punto de Venta.
     */
    public function index(Request $request)
    {
        return view('cajero.ventas.index');
    }

    /**
     * Búsqueda ultra rápida de productos (AJAX - GET)
     */
    public function buscarProductos(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $productos = Producto::where('activo', true)
            ->where(function ($q) use ($query) {
                $q->where('nombre', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%")
                  ->orWhere('codigo_barras', 'like', "%{$query}%");
            })
            ->with('categoria')
            ->limit(15)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'nombre' => $p->nombre,
                    'sku' => $p->sku,
                    'codigo_barras' => $p->codigo_barras,
                    'precio_venta' => (float) $p->precio_venta,
                    'stock_actual' => (int) $p->stock_actual,
                    'categoria' => $p->categoria?->nombre ?? 'Sin categoría',
                ];
            });

        return response()->json($productos);
    }

    /**
     * Buscar productos por SKU o nombre (AJAX)
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        $productos = Producto::where('activo', true)
            ->where(function ($q) use ($query) {
                $q->where('nombre', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%")
                  ->orWhereHas('categoria', function($qCat) use ($query) {
                      $qCat->where('nombre', 'like', "%{$query}%");
                  });
            })
            ->with('categoria')
            ->limit(20)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'nombre' => $p->nombre,
                    'sku' => $p->sku,
                    'precio_venta' => (float) $p->precio_venta,
                    'stock_actual' => (int) $p->stock_actual,
                    'categoria' => $p->categoria?->nombre ?? 'Sin categoría',
                ];
            });

        return response()->json($productos);
    }

    /**
     * Almacenar una nueva venta en la base de datos (Punto de Venta)
     */
    public function store(Request $request)
    {
        // Validar el payload de entrada
        $request->validate([
            'productos' => 'required|array|min:1',
            'productos.*.producto_id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
        ]);

        try {
            // 1. Transaccionalidad garantizada con DB::transaction
            return DB::transaction(function () use ($request) {
                $user = Auth::user();
                $esAdministrador = $user->rol === 'administrador'; // Regla estricta

                // Validar turno abierto
                $turnoActual = \App\Models\TurnoCaja::where('usuario_id', $user->id)
                                        ->where('estado', 'Abierto')
                                        ->first();

                if (!$esAdministrador && !$turnoActual) {
                    throw new \Exception("Debes abrir turno para poder cobrar.");
                }

                $total = 0;
                $detalles = [];

                // Preparación y evaluación de productos 
                foreach ($request->productos as $item) {
                    $producto = Producto::lockForUpdate()->findOrFail($item['producto_id']);
                    
                    // 2. Regla de Stock Cero
                    if (!$esAdministrador && $producto->stock_actual == 0) {
                        throw new \Exception("El producto '{$producto->nombre}' tiene stock en 0. Venta denegada.");
                    }
                    
                    // Prevención de stock insuficiente basándonos en la misma pauta (a menos que sea Administrador)
                    if (!$esAdministrador && $producto->stock_actual < $item['cantidad']) {
                        throw new \Exception("El producto '{$producto->nombre}' no cuenta con stock suficiente para vender '{$item['cantidad']}' piezas (Stock actual: {$producto->stock_actual}).");
                    }

                    // 4. Precios: Se asigna el precio correspondiente según Point of Sale base
                    $precioUnitario = $producto->precio_venta;
                    $subtotal = $precioUnitario * $item['cantidad'];
                    $total += $subtotal;

                    // 3. Sincronización: Descontar cantidad del catálogo
                    $producto->stock_actual -= $item['cantidad'];
                    $producto->save();

                    // Acumulamos información detallada en memoria para salvaguardarla posterior a la cabecera
                    $detalles[] = [
                        'producto_id' => $producto->id,
                        'cantidad' => $item['cantidad'],
                        'precio_unitario' => $precioUnitario,
                        'subtotal' => $subtotal,
                    ];
                }

                // Aplicar IVA (ejemplo: si las ventas son subtotal + IVA o ya vienen con IVA).
                // Considerando que el carrito calculó 16% extra:
                $totalConIva = $total * 1.16;

                // Generar un folio irrepetible
                $folio = 'VNT-' . date('Ymd') . '-' . strtoupper(uniqid());

                // Crear el histórico dentro de 'ventas'
                $venta = Venta::create([
                    'usuario_id' => $user->id,
                    'folio' => $folio,
                    'total' => $totalConIva,
                    'fecha' => now(),
                ]);

                // Dispersar los detalles dentro de 'detalles_venta'
                foreach ($detalles as $detalle) {
                    DetalleVenta::create([
                        'venta_id' => $venta->id,
                        'producto_id' => $detalle['producto_id'],
                        'cantidad' => $detalle['cantidad'],
                        'precio_unitario' => $detalle['precio_unitario'],
                        'subtotal' => $detalle['subtotal'],
                    ]);
                }

                // Sumar los ingresos al turno activo actual si existe (el admin podría no usar turno)
                if ($turnoActual) {
                    $turnoActual->ingresos_ventas += $totalConIva;
                    $turnoActual->efectivo_esperado = $turnoActual->fondo_inicial + $turnoActual->ingresos_ventas - $turnoActual->gastos;
                    $turnoActual->save();
                }

                return response()->json([
                    'success' => true,
                    'folio' => $folio,
                    'total' => $totalConIva,
                    'message' => 'Venta registrada con éxito'
                ]);
            });
        } catch (\Exception $e) {
            // Regresar al cliente con el mensaje de invalidación de inventario o error interno
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 422);
        }
    }
}
