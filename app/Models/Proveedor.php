<?php

namespace App\Models;

use Database\Factories\ProveedorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    /** @use HasFactory<ProveedorFactory> */
    use HasFactory;

    protected $table = 'proveedores';

    protected $fillable = [
        'nombre',
        'telefono',
        'contacto',
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public function entradasInventario()
    {
        return $this->hasMany(EntradaInventario::class);
    }
}