<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Producto>
 */
class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition(): array
    {
        return [
            'categoria_id' => Categoria::factory(),
            'proveedor_id' => Proveedor::factory(),
            'nombre' => fake()->words(3, true),
            'sku' => fake()->unique()->bothify('SKU-####-???'),
            'descripcion' => fake()->optional()->sentence(),
            'precio_compra' => fake()->randomFloat(2, 5, 100),
            'precio_venta' => fake()->randomFloat(2, 6, 150),
            'stock_actual' => fake()->numberBetween(0, 100),
            'stock_minimo' => fake()->numberBetween(0, 20),
            'tiene_caducidad' => fake()->boolean(),
            'activo' => true,
        ];
    }
}