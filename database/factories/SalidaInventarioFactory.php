<?php

namespace Database\Factories;

use App\Models\Producto;
use App\Models\SalidaInventario;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SalidaInventario>
 */
class SalidaInventarioFactory extends Factory
{
    protected $model = SalidaInventario::class;

    public function definition(): array
    {
        return [
            'producto_id' => Producto::factory(),
            'usuario_id' => User::factory(),
            'cantidad' => fake()->numberBetween(1, 20),
            'motivo' => fake()->randomElement(['MERMA', 'AJUSTE']),
            'fecha' => fake()->dateTimeBetween('-6 months', 'now'),
            'justificacion' => fake()->optional()->sentence(),
        ];
    }
}