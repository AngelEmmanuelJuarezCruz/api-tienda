<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurnoCaja extends Model
{
    use HasFactory;

    protected $table = 'turnos_caja';

    protected $fillable = [
        'usuario_id',
        'turno',
        'fondo_inicial',
        'ingresos_ventas',
        'gastos',
        'efectivo_esperado',
        'efectivo_real',
        'diferencia',
        'estado',
    ];

    protected $casts = [
        'fondo_inicial' => 'decimal:2',
        'ingresos_ventas' => 'decimal:2',
        'gastos' => 'decimal:2',
        'efectivo_esperado' => 'decimal:2',
        'efectivo_real' => 'decimal:2',
        'diferencia' => 'decimal:2',
    ];

    /**
     * El usuario/cajero que opera este turno.
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
