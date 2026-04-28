<?php

namespace App\Models;

use Database\Factories\SalidaInventarioFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalidaInventario extends Model
{
    /** @use HasFactory<SalidaInventarioFactory> */
    use HasFactory;

    protected $table = 'salidas_inventario';

    protected $fillable = [
        'producto_id',
        'usuario_id',
        'cantidad',
        'motivo',
        'fecha',
        'justificacion',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'datetime',
        ];
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}