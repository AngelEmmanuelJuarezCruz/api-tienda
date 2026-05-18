<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportesController extends Controller
{
    /**
     * Mostrar el panel de reportes.
     */
    public function index(Request $request)
    {
        $ventas = \App\Models\Venta::with('usuario')
            ->orderBy('fecha', 'desc')
            ->limit(100)
            ->get();

        $cortes = \App\Models\TurnoCaja::with('usuario')
            ->orderBy('id', 'desc')
            ->limit(50)
            ->get();

        return view('admin.reportes.index', compact('ventas', 'cortes'));
    }

    /**
     * Ajustar un corte de caja cerrado.
     */
    public function ajustarCorte(Request $request, $id)
    {
        $request->validate([
            'gastos' => 'required|numeric|min:0',
            'efectivo_real' => 'required|numeric|min:0',
        ]);

        $turno = \App\Models\TurnoCaja::findOrFail($id);

        if ($turno->estado !== 'Cerrado') {
            return back()->withErrors(['error' => 'Solo se pueden ajustar turnos que ya estén cerrados.']);
        }

        // LÓGICA DE RECALCULO CRÍTICA
        $turno->gastos = $request->gastos;
        $turno->efectivo_real = $request->efectivo_real;
        
        $turno->efectivo_esperado = $turno->fondo_inicial + $turno->ingresos_ventas - $turno->gastos;
        $turno->diferencia = $turno->efectivo_real - $turno->efectivo_esperado;
        
        $turno->save();

        return back()->with('success', 'El corte de caja ha sido ajustado y recalculado exitosamente.');
    }
}
