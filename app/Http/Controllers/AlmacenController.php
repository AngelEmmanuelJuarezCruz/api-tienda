<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\LoteProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlmacenController extends Controller
{
    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        // Validación estricta: usar solo las columnas del modelo solicitadas
        $validated = $request->validate([
            'nombre' => 'required|string|max:160|unique:productos,nombre',
            'sku' => 'nullable|string|max:100|unique:productos,sku',
            'precio_venta' => 'required|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Guardar únicamente las columnas solicitadas
            $producto = Producto::create([
                'nombre' => $validated['nombre'],
                'sku' => $validated['sku'] ?? null,
                'precio_venta' => $validated['precio_venta'],
                'stock_actual' => $validated['stock_actual'],
            ]);

            // Crear lote básico si hay stock
            if ($producto->stock_actual > 0) {
                LoteProducto::create([
                    'producto_id' => $producto->id,
                    'numero_lote' => 'LOTE-' . date('Ymd') . '-' . $producto->id,
                    'fecha_caducidad' => null,
                    'cantidad_inicial' => $producto->stock_actual,
                    'cantidad_actual' => $producto->stock_actual,
                ]);
            }

            DB::commit();

            return redirect()->route('almacen.productos')->with('success', 'Producto creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Error al crear el producto: ' . $e->getMessage()]);
        }
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, string $id)
    {
        $producto = Producto::findOrFail($id);

        $validated = $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'proveedor_id' => 'required|exists:proveedores,id',
            'nombre' => 'required|string|max:160',
            'codigo_barras' => 'required|string|max:100|unique:productos,codigo_barras,' . $producto->id,
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'unidad_medida' => 'required|in:pieza,caja,kilo,metro',
            'tiene_caducidad' => 'boolean',
            'fecha_caducidad' => 'required_if:tiene_caducidad,true|date|nullable',
        ]);

        try {
            DB::beginTransaction();

            $producto->update([
                'categoria_id' => $validated['categoria_id'],
                'proveedor_id' => $validated['proveedor_id'],
                'nombre' => $validated['nombre'],
                'codigo_barras' => $validated['codigo_barras'],
                'unidad_medida' => $validated['unidad_medida'],
                'descripcion' => $validated['descripcion'] ?? null,
                'precio_venta' => $validated['precio'],
                'tiene_caducidad' => $validated['tiene_caducidad'] ?? false,
                'stock_actual' => $validated['stock'],
            ]);

            if ($producto->tiene_caducidad && isset($validated['fecha_caducidad'])) {
                $latestLote = $producto->lotesProducto()->latest()->first();
                if ($latestLote) {
                    $latestLote->update(['fecha_caducidad' => $validated['fecha_caducidad']]);
                }
            }

            DB::commit();

            return redirect()->route('almacen.productos')->with('success', 'Producto actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Error al actualizar el producto: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(string $id)
    {
        try {
            $producto = Producto::findOrFail($id);
            $producto->delete();

            return redirect()->route('almacen.productos')->with('success', 'Producto eliminado exitosamente.');
        } catch (\Exception $e) {
            $producto = Producto::find($id);
            if ($producto) {
                $producto->update(['activo' => false]);
                return redirect()->route('almacen.productos')->with('success', 'El producto tenía dependencias, por lo que fue desactivado.');
            }
            return back()->withErrors(['error' => 'Error al eliminar el producto: ' . $e->getMessage()]);
        }
    }
}
