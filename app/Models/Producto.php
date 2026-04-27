<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'codigo_barras',
        'precio_venta',
        'precio_mayoreo',
        'stock_actual',
        'stock_minimo',
        'unidad_medida',
        'tiene_caducidad',
        'activo',
        'categoria_id',
        'proveedor_id'
    ];
}