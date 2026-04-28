<?php

namespace App\Models;

use Database\Factories\ProductoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    /** @use HasFactory<ProductoFactory> */
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'categoria_id',
        'proveedor_id',
        'nombre',
        'sku',
        'descripcion',
        'precio_compra',
        'precio_venta',
        'stock_actual',
        'stock_minimo',
        'tiene_caducidad',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'precio_compra' => 'decimal:2',
            'precio_venta' => 'decimal:2',
            'tiene_caducidad' => 'boolean',
            'activo' => 'boolean',
        ];
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function lotesProducto()
    {
        return $this->hasMany(LoteProducto::class);
    }

    public function entradasInventario()
    {
        return $this->hasMany(EntradaInventario::class);
    }

    public function salidasInventario()
    {
        return $this->hasMany(SalidaInventario::class);
    }

    public function detallesVenta()
    {
        return $this->hasMany(DetalleVenta::class);
    }

    public function alertasStock()
    {
        return $this->hasMany(AlertaStock::class);
    }

    public function bitacoraMovimientosStock()
    {
        return $this->hasMany(BitacoraMovimientoStock::class);
    }
}