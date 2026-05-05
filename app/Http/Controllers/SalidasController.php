<?php

namespace App\Http\Controllers;

use App\Models\SalidaInventario;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SalidasController extends Controller
{
    /**
     * Almacenar una nueva salida de inventario (Ej. Merma / Caducidad)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'motivo' => 'required|string|max:20', 
            'justificacion' => 'required|string|min:5', // Obligatorio para la auditoría
        ]);

        try {
            return DB::transaction(function () use ($validated) {
                $producto = Producto::lockForUpdate()->findOrFail($validated['producto_id']);

                // Validamos capacidad de inventario
                if ($producto->stock_actual < $validated['cantidad']) {
                    throw new \Exception("El stock actual ({$producto->stock_actual}) es insuficiente para procesar la merma solicitada.");
                }

                // Trazabilidad y Auditoría: Crear el registro de salida
                SalidaInventario::create([
                    'producto_id' => $producto->id,
                    'usuario_id' => Auth::id(), // Trazabilidad Obligatoria
                    'cantidad' => $validated['cantidad'],
                    'motivo' => $validated['motivo'],
                    'justificacion' => $validated['justificacion'],
                    'fecha' => now(), // Criterio de Auditoría
                ]);

                // Restar la cantidad por daño o merma
                $producto->stock_actual -= $validated['cantidad'];
                $producto->save();

                return redirect()->back()->with('success', 'Salida de inventario registrada exitosamente.');
            });
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Error al registrar la salida: ' . $e->getMessage()]);
        }
    }
}
