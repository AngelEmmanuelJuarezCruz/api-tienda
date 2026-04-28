<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== ESTADO DE BASE DE DATOS ===\n";
echo "Migraciones ejecutadas: " . DB::table('migrations')->count() . "\n";
echo "Usuarios: " . DB::table('users')->count() . "\n";
echo "Categorías: " . DB::table('categorias')->count() . "\n";
echo "Proveedores: " . DB::table('proveedores')->count() . "\n";
echo "Productos: " . DB::table('productos')->count() . "\n";
echo "Lotes: " . DB::table('lotes_producto')->count() . "\n";
echo "\n=== USUARIOS (roles) ===\n";
foreach(DB::table('users')->select('name', 'rol')->get() as $user) {
    echo "- {$user->name} ({$user->rol})\n";
}
echo "\n=== PRODUCTOS (stock bajo) ===\n";
foreach(DB::table('productos')->where('stock_actual', '<', 5)->select('nombre', 'stock_actual')->get() as $prod) {
    echo "- {$prod->nombre}: {$prod->stock_actual} unidades\n";
}
