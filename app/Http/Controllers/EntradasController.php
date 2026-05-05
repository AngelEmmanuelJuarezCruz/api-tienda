<?php

namespace App\Http\Controllers;

use App\Models\EntradaInventario;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EntradasController extends Controller
{
    /**
     * Almacenar una nueva entrada de inventario (Ej. compra a proveedor)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'proveedor_id' => 'required|exists:proveedores,id',
            'cantidad' => 'required|integer|min:1',
            'costo_adquisicion' => 'required|numeric|min:0',
            'notas' => 'nullable|string',
        ]);

        try {
            return DB::transaction(function () use ($validated) {
                // Recuperar producto empleando lockForUpdate para evitar carreras
                $producto = Producto::lockForUpdate()->findOrFail($validated['producto_id']);

                // Trazabilidad y Auditoría: Crear el registro de entrada
                EntradaInventario::create([
                    'producto_id' => $producto->id,
                    'proveedor_id' => $validated['proveedor_id'],
                    'usuario_id' => Auth::id(), // Trazabilidad Obligatoria
                    'cantidad' => $validated['cantidad'],
                    'costo_unitario' => $validated['costo_adquisicion'], // Cálculo de márgenes
                    'fecha' => now(), // Criterio de Auditoría
                    'notas' => $validated['notas'] ?? null,
                ]);

                // Sumar la cantidad adquirida al inventario disponible
                $producto->stock_actual += $validated['cantidad'];
                $producto->precio_compra = $validated['costo_adquisicion'];
                $producto->save();

                return redirect()->back()->with('success', 'Entrada de inventario registrada con éxito.');
            });
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Error al registrar la entrada: ' . $e->getMessage()]);
        }
    }
}
