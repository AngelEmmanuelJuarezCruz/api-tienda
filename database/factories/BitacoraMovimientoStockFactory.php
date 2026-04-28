<?php

namespace Database\Factories;

use App\Models\BitacoraMovimientoStock;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BitacoraMovimientoStock>
 */
class BitacoraMovimientoStockFactory extends Factory
{
    protected $model = BitacoraMovimientoStock::class;

    public function definition(): array
    {
        $cantidad = fake()->numberBetween(1, 25);
        $stockAntes = fake()->numberBetween($cantidad, 200);
        $origen = fake()->randomElement(['ENTRADA', 'SALIDA', 'VENTA']);
        $stockDespues = $origen === 'ENTRADA'
            ? $stockAntes + $cantidad
            : max(0, $stockAntes - $cantidad);

        return [
            'producto_id' => Producto::factory(),
            'origen' => $origen,
            'cantidad' => $cantidad,
            'stock_antes' => $stockAntes,
            'stock_despues' => $stockDespues,
            'referencia' => fake()->optional()->bothify('REF-####'),
            'fecha_movimiento' => now(),
        ];
    }
}