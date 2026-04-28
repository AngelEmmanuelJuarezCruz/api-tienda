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

    // ========== SCOPES ÚTILES PARA FILTROS ==========

    /**
     * Filtrar productos con stock bajo (por debajo del mínimo)
     */
    public function scopeStockBajo($query)
    {
        return $query->whereColumn('stock_actual', '<', 'stock_minimo');
    }

    /**
     * Filtrar productos activos solamente
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Filtrar por categoría
     */
    public function scopePorCategoria($query, $categoriaId)
    {
        return $query->where('categoria_id', $categoriaId);
    }

    /**
     * Filtrar por proveedor
     */
    public function scopePorProveedor($query, $proveedorId)
    {
        return $query->where('proveedor_id', $proveedorId);
    }

    /**
     * Filtrar productos con fecha de caducidad (próximos 7 días)
     */
    public function scopeProximosACaducar($query, $dias = 7)
    {
        $fecha = now()->addDays($dias);
        return $query->where('tiene_caducidad', true)
                    ->whereNotNull('fecha_caducidad')
                    ->whereBetween('fecha_caducidad', [now(), $fecha]);
    }

    /**
     * Productos ordenados por stock bajo (descendente)
     */
    public function scopeOrdenadosPorStock($query)
    {
        return $query->orderBy('stock_actual', 'asc');
    }
}