<?php

namespace App\Http\Controllers;

use App\Models\TurnoCaja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CajaController extends Controller
{
    /**
     * Mostrar el panel de control de caja para el cajero.
     */
    public function index()
    {
        $usuario = Auth::user();
        
        // Obtener el turno abierto actual
        $turnoActual = TurnoCaja::where('usuario_id', $usuario->id)
                                ->where('estado', 'Abierto')
                                ->first();

        // Obtener el historial de turnos recientes de este cajero
        $historial = TurnoCaja::where('usuario_id', $usuario->id)
                                ->orderBy('id', 'desc')
                                ->limit(5)
                                ->get();

        return view('cajero.caja.index', compact('turnoActual', 'historial'));
    }

    /**
     * Abrir un nuevo turno de caja.
     */
    public function abrir(Request $request)
    {
        $request->validate([
            'turno' => 'required|string|max:100',
            'fondo_inicial' => 'required|numeric|min:0',
        ]);

        $usuario = Auth::user();

        // Verificar que no tenga ya un turno abierto
        $turnoExistente = TurnoCaja::where('usuario_id', $usuario->id)
                                    ->where('estado', 'Abierto')
                                    ->exists();

        if ($turnoExistente) {
            return back()->withErrors(['error' => 'Ya tienes un turno abierto actualmente. Ciérralo primero.']);
        }

        TurnoCaja::create([
            'usuario_id' => $usuario->id,
            'turno' => $request->turno,
            'fondo_inicial' => $request->fondo_inicial,
            'efectivo_esperado' => $request->fondo_inicial, // Al inicio, el esperado es el fondo inicial
            'estado' => 'Abierto',
        ]);

        return redirect()->route('cajero.caja.index')->with('success', 'Turno abierto exitosamente. ¡Excelente jornada!');
    }

    /**
     * Registrar un gasto menor de la caja.
     */
    public function registrarGasto(Request $request)
    {
        $request->validate([
            'monto_gasto' => 'required|numeric|min:0.01',
            'descripcion_gasto' => 'required|string|max:255',
        ]);

        $usuario = Auth::user();
        $turnoActual = TurnoCaja::where('usuario_id', $usuario->id)
                                ->where('estado', 'Abierto')
                                ->firstOrFail();

        try {
            DB::beginTransaction();

            $turnoActual->gastos += $request->monto_gasto;
            $turnoActual->efectivo_esperado = $turnoActual->fondo_inicial + $turnoActual->ingresos_ventas - $turnoActual->gastos;
            $turnoActual->save();

            // Nota: Aquí se podría crear también un registro en una tabla `gastos_caja` para tener la bitácora del detalle.
            // Por simplicidad del requerimiento, actualizamos la columna directamente en el turno.

            DB::commit();

            return redirect()->route('cajero.caja.index')->with('success', 'Gasto registrado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ocurrió un error al registrar el gasto: ' . $e->getMessage()]);
        }
    }

    /**
     * Cerrar el turno de caja (Hacer Corte).
     */
    public function cerrar(Request $request)
    {
        $request->validate([
            'efectivo_real' => 'required|numeric|min:0',
        ]);

        $usuario = Auth::user();
        $turnoActual = TurnoCaja::where('usuario_id', $usuario->id)
                                ->where('estado', 'Abierto')
                                ->firstOrFail();

        $efectivo_real = $request->efectivo_real;
        
        // El esperado actual
        $efectivo_esperado = $turnoActual->fondo_inicial + $turnoActual->ingresos_ventas - $turnoActual->gastos;
        
        // Diferencia: Positivo si sobra dinero, Negativo si falta dinero.
        $diferencia = $efectivo_real - $efectivo_esperado;

        $turnoActual->update([
            'efectivo_real' => $efectivo_real,
            'efectivo_esperado' => $efectivo_esperado, // Re-calculamos por si acaso
            'diferencia' => $diferencia,
            'estado' => 'Cerrado',
            // updated_at se actualiza automático dando la fecha de cierre
        ]);

        return redirect()->route('cajero.caja.index')->with('success', 'Corte de caja realizado y turno cerrado correctamente.');
    }
}
