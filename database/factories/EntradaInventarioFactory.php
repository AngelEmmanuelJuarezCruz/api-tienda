<?php

namespace Database\Factories;

use App\Models\EntradaInventario;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EntradaInventario>
 */
class EntradaInventarioFactory extends Factory
{
    protected $model = EntradaInventario::class;

    public function definition(): array
    {
        return [
            'producto_id' => Producto::factory(),
            'usuario_id' => User::factory(),
            'proveedor_id' => Proveedor::factory(),
            'cantidad' => fake()->numberBetween(1, 50),
            'costo_unitario' => fake()->randomFloat(2, 1, 100),
            'fecha' => fake()->dateTimeBetween('-6 months', 'now'),
            'notas' => fake()->optional()->sentence(),
        ];
    }
}