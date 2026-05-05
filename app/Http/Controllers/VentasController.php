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

                // Generar un folio irrepetible
                $folio = 'VNT-' . date('Ymd') . '-' . strtoupper(uniqid());

                // Crear el histórico dentro de 'ventas'
                $venta = Venta::create([
                    'usuario_id' => $user->id,
                    'folio' => $folio,
                    'total' => $total,
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

                return redirect()->route('ventas.dashboard')->with('success', 'Venta registrada con éxito bajo folio: ' . $folio);
            });
        } catch (\Exception $e) {
            // Regresar al cliente con el mensaje de invalidación de inventario o error interno
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
