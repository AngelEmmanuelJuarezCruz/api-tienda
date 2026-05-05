<?php
/**
 * SCRIPT DE RESET DE BD - USO: php artisan tinker
 * 
 * Dentro de tinker, ejecuta:
 * >>> include 'reset_db.php'
 * 
 * ¡Advertencia! Esto borrará TODOS los datos y reiniciará la BD.
 */

use Illuminate\Support\Facades\Artisan;

echo "\n=== RESET DE BASE DE DATOS ===\n";
echo "⚠️  ESTO BORRARÁ TODOS LOS DATOS. ¿Continuar? (S/n): ";

$response = trim(fgets(STDIN));
if (strtolower($response) !== 's' && $response !== '') {
    echo "✗ Cancelado.\n";
    return;
}

echo "\n🔄 Ejecutando migraciones...\n";
Artisan::call('migrate:refresh', ['--force' => true]);
echo Artisan::output();

echo "\n🌱 Ejecutando seeders...\n";
Artisan::call('db:seed', ['--force' => true]);
echo Artisan::output();

echo "\n✅ Base de datos reseteada completamente.\n";
echo "📊 Conteos:\n";
echo "- Usuarios: " . DB::table('users')->count() . "\n";
echo "- Categorías: " . DB::table('categorias')->count() . "\n";
echo "- Productos: " . DB::table('productos')->count() . "\n";
echo "- Proveedores: " . DB::table('proveedores')->count() . "\n";
