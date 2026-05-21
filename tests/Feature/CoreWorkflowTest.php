<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\DetalleVenta;
use App\Models\EntradaInventario;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\SalidaInventario;
use App\Models\TurnoCaja;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoreWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_redirects_users_by_role(): void
    {
        $roles = [
            'dueno' => route('admin.index', absolute: false),
            'administrador' => route('admin.index', absolute: false),
            'encargado' => route('encargado.dashboard', absolute: false),
            'cajero' => route('cajero.dashboard', absolute: false),
        ];

        foreach ($roles as $rol => $expectedPath) {
            $user = User::factory()->create([
                'rol' => $rol,
                'email' => "{$rol}@example.test",
            ]);

            $this->post(route('login.post'), [
                'email' => $user->email,
                'password' => 'password',
            ])->assertRedirect($expectedPath);

            auth()->logout();
        }
    }

    public function test_encargado_can_create_product(): void
    {
        $user = User::factory()->encargado()->create();
        $categoria = Categoria::factory()->create();
        $proveedor = Proveedor::factory()->create();

        $response = $this->actingAs($user)->post(route('almacen.productos.store'), [
            'categoria_id' => $categoria->id,
            'proveedor_id' => $proveedor->id,
            'nombre' => 'Guantes latex prueba',
            'sku' => 'SKU-TEST-001',
            'precio_venta' => 125.50,
            'stock_actual' => 12,
            'fecha_caducidad' => now()->addYear()->toDateString(),
        ]);

        $response->assertRedirect(route('almacen.productos', absolute: false));

        $this->assertDatabaseHas('productos', [
            'nombre' => 'Guantes latex prueba',
            'sku' => 'SKU-TEST-001',
            'stock_actual' => 12,
        ]);

        $this->assertDatabaseHas('lotes_producto', [
            'cantidad_inicial' => 12,
            'cantidad_actual' => 12,
        ]);
    }

    public function test_inventory_entry_increases_stock_and_records_traceability(): void
    {
        $user = User::factory()->encargado()->create();
        $proveedor = Proveedor::factory()->create();
        $producto = Producto::factory()->create([
            'proveedor_id' => $proveedor->id,
            'stock_actual' => 5,
            'precio_compra' => 10,
        ]);

        $response = $this->actingAs($user)->from(route('almacen.entradas'))->post(route('almacen.entradas.store'), [
            'producto_id' => $producto->id,
            'proveedor_id' => $proveedor->id,
            'cantidad' => 7,
            'costo_adquisicion' => 22.25,
            'notas' => 'Compra de prueba',
        ]);

        $response->assertRedirect(route('almacen.entradas', absolute: false));

        $this->assertDatabaseHas('entradas_inventario', [
            'producto_id' => $producto->id,
            'proveedor_id' => $proveedor->id,
            'usuario_id' => $user->id,
            'cantidad' => 7,
            'costo_unitario' => 22.25,
        ]);

        $this->assertSame(12, $producto->fresh()->stock_actual);
    }

    public function test_inventory_output_decreases_stock_and_blocks_insufficient_stock(): void
    {
        $user = User::factory()->encargado()->create();
        $producto = Producto::factory()->create(['stock_actual' => 10]);

        $response = $this->actingAs($user)->from(route('almacen.salidas'))->post(route('almacen.salidas.store'), [
            'producto_id' => $producto->id,
            'cantidad' => 4,
            'motivo' => 'MERMA',
            'justificacion' => 'Producto danado en almacen',
        ]);

        $response->assertRedirect(route('almacen.salidas', absolute: false));

        $this->assertDatabaseHas('salidas_inventario', [
            'producto_id' => $producto->id,
            'usuario_id' => $user->id,
            'cantidad' => 4,
            'motivo' => 'MERMA',
        ]);

        $this->assertSame(6, $producto->fresh()->stock_actual);

        $this->actingAs($user)->from(route('almacen.salidas'))->post(route('almacen.salidas.store'), [
            'producto_id' => $producto->id,
            'cantidad' => 99,
            'motivo' => 'MERMA',
            'justificacion' => 'Intento mayor al stock',
        ])->assertSessionHasErrors('error');

        $this->assertSame(6, $producto->fresh()->stock_actual);
    }

    public function test_pos_sale_creates_sale_details_updates_stock_and_turn(): void
    {
        $user = User::factory()->cajero()->create();
        $producto = Producto::factory()->create([
            'stock_actual' => 9,
            'precio_venta' => 100,
        ]);
        $turno = TurnoCaja::create([
            'usuario_id' => $user->id,
            'turno' => 'Matutino',
            'fondo_inicial' => 50,
            'estado' => 'Abierto',
        ]);

        $response = $this->actingAs($user)->postJson(route('pos.cobrar'), [
            'productos' => [
                ['producto_id' => $producto->id, 'cantidad' => 2],
            ],
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true);

        $this->assertEqualsWithDelta(232, $response->json('total'), 0.01);

        $this->assertDatabaseCount('ventas', 1);
        $this->assertDatabaseHas('detalles_venta', [
            'producto_id' => $producto->id,
            'cantidad' => 2,
            'precio_unitario' => 100,
            'subtotal' => 200,
        ]);
        $this->assertSame(7, $producto->fresh()->stock_actual);

        $turno->refresh();
        $this->assertSame('232.00', $turno->ingresos_ventas);
        $this->assertSame('282.00', $turno->efectivo_esperado);
    }

    public function test_pos_sale_requires_open_shift_for_cashier(): void
    {
        $user = User::factory()->cajero()->create();
        $producto = Producto::factory()->create(['stock_actual' => 5]);

        $this->actingAs($user)->postJson(route('pos.cobrar'), [
            'productos' => [
                ['producto_id' => $producto->id, 'cantidad' => 1],
            ],
        ])->assertStatus(422)
            ->assertJsonPath('success', false);

        $this->assertDatabaseCount('ventas', 0);
        $this->assertSame(5, $producto->fresh()->stock_actual);
    }

    public function test_admin_can_export_sales_report_as_pdf_excel_and_zip(): void
    {
        $admin = User::factory()->create(['rol' => 'administrador']);
        $producto = Producto::factory()->create();
        $venta = Venta::factory()->create([
            'usuario_id' => $admin->id,
            'fecha' => now(),
            'total' => 150,
        ]);
        DetalleVenta::factory()->create([
            'venta_id' => $venta->id,
            'producto_id' => $producto->id,
            'cantidad' => 2,
            'precio_unitario' => 75,
            'subtotal' => 150,
        ]);
        EntradaInventario::factory()->create([
            'producto_id' => $producto->id,
            'usuario_id' => $admin->id,
            'fecha' => now(),
        ]);
        SalidaInventario::factory()->create([
            'producto_id' => $producto->id,
            'usuario_id' => $admin->id,
            'fecha' => now(),
            'justificacion' => 'Salida de prueba',
        ]);

        foreach (['pdf', 'excel', 'zip'] as $formato) {
            $response = $this->actingAs($admin)->get(route('admin.reportes.export', [
                'tipo' => 'ventas',
                'formato' => $formato,
                'desde' => now()->subDay()->toDateString(),
                'hasta' => now()->addDay()->toDateString(),
            ]));

            $response->assertOk();
        }
    }
}
