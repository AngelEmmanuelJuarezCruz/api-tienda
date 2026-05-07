<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\LoteProducto;
use App\Models\Categoria;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AlmacenController extends Controller
{
    /**
     * Store a newly created product in storage.
     */
    public function create()
    {
        $categorias = Categoria::all();
        return view('admin.almacen.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        // Validación estricta: usar solo las columnas del modelo solicitadas
        $validated = $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'nombre' => 'required|string|max:160|unique:productos,nombre',
            'sku' => 'required|string|max:100|unique:productos,sku',
            'precio_venta' => 'required|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
            'imagen' => 'nullable|image|max:4096',
        ]);

        try {
            DB::beginTransaction();

            // Guardar únicamente las columnas solicitadas
            // Asegurar un proveedor válido: usar proveedor enviado o el primer proveedor existente;
            // si no existe ninguno, crear uno por defecto para evitar fallo NOT NULL.
            $firstProveedor = Proveedor::first();
            if (!$firstProveedor) {
                $firstProveedor = Proveedor::create(['nombre' => 'Proveedor por defecto']);
            }

            $imagenPath = null;
            if ($request->hasFile('imagen')) {
                $imagenPath = $request->file('imagen')->store('productos', 'public');
            }

            $producto = Producto::create([
                'categoria_id' => $validated['categoria_id'],
                'proveedor_id' => $firstProveedor->id,
                'nombre' => $validated['nombre'],
                'sku' => $validated['sku'],
                'precio_venta' => $validated['precio_venta'],
                'stock_actual' => $validated['stock_actual'],
                'imagen_path' => $imagenPath,
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
            'categoria_id' => 'nullable|exists:categorias,id',
            'proveedor_id' => 'nullable|exists:proveedores,id',
            'nombre' => 'required|string|max:160',
            'codigo_barras' => 'nullable|string|max:100|unique:productos,codigo_barras,' . $producto->id,
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'unidad_medida' => 'nullable|in:pieza,caja,kilo,metro',
            'tiene_caducidad' => 'boolean',
            'fecha_caducidad' => 'nullable|date|required_if:tiene_caducidad,true',
            'imagen' => 'nullable|image|max:4096',
        ]);

        try {
            DB::beginTransaction();

            $imagenPath = $producto->imagen_path;
            if ($request->hasFile('imagen')) {
                if ($producto->imagen_path) {
                    Storage::disk('public')->delete($producto->imagen_path);
                }

                $imagenPath = $request->file('imagen')->store('productos', 'public');
            }

            $producto->update([
                'categoria_id' => $validated['categoria_id'] ?? $producto->categoria_id,
                'proveedor_id' => $validated['proveedor_id'] ?? $producto->proveedor_id,
                'nombre' => $validated['nombre'],
                'codigo_barras' => $validated['codigo_barras'] ?? $producto->codigo_barras,
                'unidad_medida' => $validated['unidad_medida'] ?? $producto->unidad_medida,
                'descripcion' => $validated['descripcion'] ?? null,
                'precio_venta' => $validated['precio'],
                'tiene_caducidad' => $validated['tiene_caducidad'] ?? false,
                'stock_actual' => $validated['stock'],
                'imagen_path' => $imagenPath,
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
