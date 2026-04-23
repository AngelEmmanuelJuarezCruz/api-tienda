<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductoSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $proveedores = [
            ['nombre' => 'Distribuidora Centro', 'telefono' => '7710001001', 'contacto' => 'Ana Lopez'],
            ['nombre' => 'Alimentos del Valle', 'telefono' => '7710001002', 'contacto' => 'Marco Ruiz'],
            ['nombre' => 'Higiene Total', 'telefono' => '7710001003', 'contacto' => 'Laura Soto'],
        ];

        foreach ($proveedores as $proveedor) {
            DB::table('proveedores')->updateOrInsert(
                ['nombre' => $proveedor['nombre']],
                [
                    'telefono' => $proveedor['telefono'],
                    'contacto' => $proveedor['contacto'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        $categoriaIds = DB::table('categorias')->pluck('id', 'nombre');
        $proveedorIds = DB::table('proveedores')->pluck('id', 'nombre');

        $productos = [
            [
                'categoria' => 'Abarrotes',
                'proveedor' => 'Distribuidora Centro',
                'nombre' => 'Arroz Super Extra 1kg',
                'sku' => 'ABR-ARROZ-1KG',
                'precio_compra' => 18.50,
                'precio_venta' => 24.00,
                'stock_actual' => 5,
                'stock_minimo' => 10,
                'tiene_caducidad' => false,
            ],
            [
                'categoria' => 'Lacteos',
                'proveedor' => 'Alimentos del Valle',
                'nombre' => 'Leche Entera 1L',
                'sku' => 'LAC-LECHE-1L',
                'precio_compra' => 19.90,
                'precio_venta' => 26.50,
                'stock_actual' => 18,
                'stock_minimo' => 8,
                'tiene_caducidad' => true,
            ],
            [
                'categoria' => 'Limpieza',
                'proveedor' => 'Higiene Total',
                'nombre' => 'Jabon en Polvo 850g',
                'sku' => 'LIM-JABON-850',
                'precio_compra' => 32.00,
                'precio_venta' => 43.50,
                'stock_actual' => 12,
                'stock_minimo' => 7,
                'tiene_caducidad' => false,
            ],
            [
                'categoria' => 'Bebidas',
                'proveedor' => 'Distribuidora Centro',
                'nombre' => 'Refresco Cola 2L',
                'sku' => 'BEB-COLA-2L',
                'precio_compra' => 25.00,
                'precio_venta' => 33.00,
                'stock_actual' => 20,
                'stock_minimo' => 12,
                'tiene_caducidad' => true,
            ],
            [
                'categoria' => 'Dulceria',
                'proveedor' => 'Distribuidora Centro',
                'nombre' => 'Chocolate de Mesa 90g',
                'sku' => 'DUL-CHOCO-90',
                'precio_compra' => 9.00,
                'precio_venta' => 13.00,
                'stock_actual' => 22,
                'stock_minimo' => 10,
                'tiene_caducidad' => true,
            ],
            [
                'categoria' => 'Abarrotes',
                'proveedor' => 'Alimentos del Valle',
                'nombre' => 'Frijol Negro 900g',
                'sku' => 'ABR-FRIJOL-900',
                'precio_compra' => 22.00,
                'precio_venta' => 30.00,
                'stock_actual' => 16,
                'stock_minimo' => 9,
                'tiene_caducidad' => false,
            ],
            [
                'categoria' => 'Limpieza',
                'proveedor' => 'Higiene Total',
                'nombre' => 'Cloro 1L',
                'sku' => 'LIM-CLORO-1L',
                'precio_compra' => 14.00,
                'precio_venta' => 21.00,
                'stock_actual' => 4,
                'stock_minimo' => 6,
                'tiene_caducidad' => false,
            ],
            [
                'categoria' => 'Bebidas',
                'proveedor' => 'Alimentos del Valle',
                'nombre' => 'Agua Natural 1L',
                'sku' => 'BEB-AGUA-1L',
                'precio_compra' => 8.50,
                'precio_venta' => 12.00,
                'stock_actual' => 30,
                'stock_minimo' => 15,
                'tiene_caducidad' => false,
            ],
        ];

        foreach ($productos as $producto) {
            DB::table('productos')->updateOrInsert(
                ['sku' => $producto['sku']],
                [
                    'categoria_id' => $categoriaIds[$producto['categoria']] ?? null,
                    'proveedor_id' => $proveedorIds[$producto['proveedor']] ?? null,
                    'nombre' => $producto['nombre'],
                    'descripcion' => null,
                    'precio_compra' => $producto['precio_compra'],
                    'precio_venta' => $producto['precio_venta'],
                    'stock_actual' => $producto['stock_actual'],
                    'stock_minimo' => $producto['stock_minimo'],
                    'tiene_caducidad' => $producto['tiene_caducidad'],
                    'activo' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
