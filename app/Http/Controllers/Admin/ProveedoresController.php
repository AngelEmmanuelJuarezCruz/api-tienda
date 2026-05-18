<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedoresController extends Controller
{
    /**
     * Mostrar listado de proveedores.
     */
    public function index(Request $request)
    {
        $query = $request->get('q');
        
        $proveedores = Proveedor::when($query, function($q) use ($query) {
            $q->where('nombre', 'like', "%{$query}%")
              ->orWhere('contacto', 'like', "%{$query}%");
        })->orderBy('nombre', 'asc')->get();

        return view('admin.proveedores.index', compact('proveedores', 'query'));
    }

    /**
     * Crear un nuevo proveedor.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:140',
            'contacto' => 'nullable|string|max:140',
            'telefono' => 'nullable|string|max:30',
        ]);

        Proveedor::create($request->only('nombre', 'contacto', 'telefono'));

        return redirect()->route('admin.proveedores.index')->with('success', 'Proveedor registrado exitosamente.');
    }

    /**
     * Actualizar proveedor existente.
     */
    public function update(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'nombre' => 'required|string|max:140',
            'contacto' => 'nullable|string|max:140',
            'telefono' => 'nullable|string|max:30',
        ]);

        $proveedor->update($request->only('nombre', 'contacto', 'telefono'));

        return redirect()->route('admin.proveedores.index')->with('success', 'Proveedor actualizado correctamente.');
    }

    /**
     * Eliminar proveedor de la BDD.
     */
    public function destroy(Proveedor $proveedor)
    {
        // Validación para evitar borrar proveedores con dependencias operativas
        if ($proveedor->productos()->exists()) {
            return redirect()->back()->with('error', 'No se puede eliminar este proveedor porque tiene insumos médicos asociados al inventario.');
        }

        $proveedor->delete();

        return redirect()->route('admin.proveedores.index')->with('success', 'Proveedor eliminado del directorio.');
    }
}
