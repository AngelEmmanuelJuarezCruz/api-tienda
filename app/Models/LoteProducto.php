<?php

namespace App\Models;

use Database\Factories\LoteProductoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoteProducto extends Model
{
    /** @use HasFactory<LoteProductoFactory> */
    use HasFactory;

    protected $table = 'lotes_producto';

    protected $fillable = [
        'producto_id',
        'numero_lote',
        'fecha_caducidad',
        'cantidad_inicial',
        'cantidad_actual',
    ];

    protected function casts(): array
    {
        return [
            'fecha_caducidad' => 'date',
        ];
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}