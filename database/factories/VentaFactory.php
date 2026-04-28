<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Venta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Venta>
 */
class VentaFactory extends Factory
{
    protected $model = Venta::class;

    public function definition(): array
    {
        return [
            'usuario_id' => User::factory(),
            'folio' => fake()->unique()->bothify('VTA-########'),
            'total' => fake()->randomFloat(2, 10, 500),
            'fecha' => fake()->dateTimeBetween('-3 months', 'now'),
        ];
    }
}