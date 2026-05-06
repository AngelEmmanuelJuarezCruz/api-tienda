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
            ['nombre' => 'MedSupply Central', 'telefono' => '7718001001', 'contacto' => 'Ana Lopez'],
            ['nombre' => 'Laboratorios Delta', 'telefono' => '7718001002', 'contacto' => 'Marco Ruiz'],
            ['nombre' => 'ClinicaPro Distribuciones', 'telefono' => '7718001003', 'contacto' => 'Laura Soto'],
            ['nombre' => 'BioSafe EPP', 'telefono' => '7718001004', 'contacto' => 'Carlos Mendez'],
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
                'categoria' => 'EPP',
                'proveedor' => 'BioSafe EPP',
                'nombre' => 'Guantes de latex M',
                'sku' => 'EPP-GUANTES-M',
                'precio_compra' => 45.00,
                'precio_venta' => 65.00,
                'stock_actual' => 12,
                'stock_minimo' => 20,
                'tiene_caducidad' => false,
            ],
            [
                'categoria' => 'Material de Curacion',
                'proveedor' => 'ClinicaPro Distribuciones',
                'nombre' => 'Gasa esteril 10x10',
                'sku' => 'CUR-GASA-10X10',
                'precio_compra' => 12.00,
                'precio_venta' => 18.00,
                'stock_actual' => 9,
                'stock_minimo' => 15,
                'tiene_caducidad' => true,
            ],
            [
                'categoria' => 'Diagnostico',
                'proveedor' => 'MedSupply Central',
                'nombre' => 'Termometro digital',
                'sku' => 'DIA-TERM-001',
                'precio_compra' => 95.00,
                'precio_venta' => 140.00,
                'stock_actual' => 7,
                'stock_minimo' => 10,
                'tiene_caducidad' => false,
            ],
            [
                'categoria' => 'Diagnostico',
                'proveedor' => 'MedSupply Central',
                'nombre' => 'Oximetro de pulso',
                'sku' => 'DIA-OXI-001',
                'precio_compra' => 520.00,
                'precio_venta' => 780.00,
                'stock_actual' => 6,
                'stock_minimo' => 5,
                'tiene_caducidad' => false,
            ],
            [
                'categoria' => 'Desinfectantes',
                'proveedor' => 'ClinicaPro Distribuciones',
                'nombre' => 'Alcohol isopropilico 70% 1L',
                'sku' => 'DES-ALC-1L',
                'precio_compra' => 45.00,
                'precio_venta' => 68.00,
                'stock_actual' => 4,
                'stock_minimo' => 8,
                'tiene_caducidad' => true,
            ],
            [
                'categoria' => 'Medicamentos',
                'proveedor' => 'Laboratorios Delta',
                'nombre' => 'Solucion salina 0.9% 500ml',
                'sku' => 'MED-SS-500',
                'precio_compra' => 28.00,
                'precio_venta' => 40.00,
                'stock_actual' => 14,
                'stock_minimo' => 12,
                'tiene_caducidad' => true,
            ],
            [
                'categoria' => 'Dispositivos Medicos',
                'proveedor' => 'MedSupply Central',
                'nombre' => 'Jeringa 5ml',
                'sku' => 'DIS-JER-5ML',
                'precio_compra' => 2.20,
                'precio_venta' => 3.80,
                'stock_actual' => 60,
                'stock_minimo' => 40,
                'tiene_caducidad' => false,
            ],
            [
                'categoria' => 'Insumos Quirurgicos',
                'proveedor' => 'ClinicaPro Distribuciones',
                'nombre' => 'Bata desechable',
                'sku' => 'QUI-BATA-DES',
                'precio_compra' => 18.00,
                'precio_venta' => 28.00,
                'stock_actual' => 25,
                'stock_minimo' => 20,
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
