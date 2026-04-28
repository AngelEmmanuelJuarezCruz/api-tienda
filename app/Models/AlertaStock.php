<?php

namespace App\Models;

use Database\Factories\AlertaStockFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertaStock extends Model
{
    /** @use HasFactory<AlertaStockFactory> */
    use HasFactory;

    protected $table = 'alertas_stock';

    protected $fillable = [
        'producto_id',
        'tipo',
        'atendida',
        'mensaje',
        'fecha_alerta',
    ];

    protected function casts(): array
    {
        return [
            'atendida' => 'boolean',
            'fecha_alerta' => 'datetime',
        ];
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}