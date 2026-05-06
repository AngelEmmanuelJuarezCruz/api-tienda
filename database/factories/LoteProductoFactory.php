<?php

namespace Database\Factories;

use App\Models\LoteProducto;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LoteProducto>
 */
class LoteProductoFactory extends Factory
{
    protected $model = LoteProducto::class;

    public function definition(): array
    {
        $cantidad = fake()->numberBetween(1, 100);

        return [
            'producto_id' => Producto::factory(),
            'numero_lote' => fake()->unique()->bothify('LOT-########'),
            'fecha_caducidad' => fake()->optional()->dateTimeBetween('+1 month', '+12 months'),
            'cantidad_inicial' => $cantidad,
            'cantidad_actual' => fake()->numberBetween(0, $cantidad),
        ];
    }
}