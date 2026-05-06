<?php

namespace Database\Factories;

use App\Models\AlertaStock;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AlertaStock>
 */
class AlertaStockFactory extends Factory
{
    protected $model = AlertaStock::class;

    public function definition(): array
    {
        return [
            'producto_id' => Producto::factory(),
            'tipo' => fake()->randomElement(['STOCK_BAJO', 'SIN_STOCK']),
            'atendida' => fake()->boolean(),
            'mensaje' => fake()->sentence(),
            'fecha_alerta' => now(),
        ];
    }
}