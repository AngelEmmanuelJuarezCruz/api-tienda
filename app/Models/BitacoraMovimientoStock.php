<?php

namespace App\Models;

use Database\Factories\BitacoraMovimientoStockFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BitacoraMovimientoStock extends Model
{
    /** @use HasFactory<BitacoraMovimientoStockFactory> */
    use HasFactory;

    protected $table = 'bitacora_movimientos_stock';

    protected $fillable = [
        'producto_id',
        'origen',
        'cantidad',
        'stock_antes',
        'stock_despues',
        'referencia',
        'fecha_movimiento',
    ];

    protected function casts(): array
    {
        return [
            'fecha_movimiento' => 'datetime',
        ];
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}