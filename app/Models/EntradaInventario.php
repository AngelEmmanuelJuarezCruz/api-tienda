<?php

namespace App\Models;

use Database\Factories\EntradaInventarioFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntradaInventario extends Model
{
    /** @use HasFactory<EntradaInventarioFactory> */
    use HasFactory;

    protected $table = 'entradas_inventario';

    protected $fillable = [
        'producto_id',
        'usuario_id',
        'proveedor_id',
        'cantidad',
        'costo_unitario',
        'fecha',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'costo_unitario' => 'decimal:2',
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

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }
}