<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AlmacenController;
use App\Http\Controllers\Admin\ProveedoresController;
use App\Http\Controllers\VentasController;
use App\Http\Controllers\Admin\UsuariosController;
use App\Http\Controllers\Admin\ReportesController;
use App\Http\Controllers\ProductoController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Ruta temporal ver su diseño Frontend
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
// Rutas de autenticación
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas compartidas de POS (Accesible por admin y cajero)
Route::middleware(['auth'])->group(function () {
    Route::get('/pos/buscar-productos', [VentasController::class, 'buscarProductos'])->name('pos.buscar-productos');
    Route::post('/pos/cobrar', [VentasController::class, 'store'])->name('pos.cobrar');
});

// Grupos de rutas protegidos por inicio de sesión (auth) y perfil de seguridad (role)
Route::middleware(['auth', 'role:dueno,administrador'])->prefix('admin')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('admin.index');

    // Almacén - accesible a encargado y roles administrativos
    Route::middleware(['role:encargado,dueno,administrador'])->group(function () {
        Route::get('/almacen', [AlmacenController::class, 'index'])->name('admin.almacen.index');
    });

    // Proveedores - solo accesible por Dueño/Administrador
    Route::middleware(['role:dueno,administrador'])->group(function () {
        Route::get('/proveedores', [ProveedoresController::class, 'index'])->name('admin.proveedores.index');
        Route::post('/proveedores', [ProveedoresController::class, 'store'])->name('admin.proveedores.store');
        Route::put('/proveedores/{proveedor}', [ProveedoresController::class, 'update'])->name('admin.proveedores.update');
        Route::delete('/proveedores/{proveedor}', [ProveedoresController::class, 'destroy'])->name('admin.proveedores.destroy');
    });
});

// Ventas: movida bajo /admin y accesible para el Dueño
Route::middleware(['auth', 'role:dueno'])->prefix('admin')->group(function () {
    Route::get('/ventas', [VentasController::class, 'index'])->name('admin.ventas.index');
    // Usuarios - acceso exclusivo del Dueño
    Route::get('/usuarios', [UsuariosController::class, 'index'])->name('admin.usuarios.index');
    // Reportes - panel de inteligencia (Dueño y Admin)
    Route::get('/reportes', [ReportesController::class, 'index'])->name('admin.reportes.index');
    Route::put('/reportes/caja/{id}/ajustar', [ReportesController::class, 'ajustarCorte'])->name('admin.reportes.ajustar');
});
Route::get('/encargado/dashboard', function () {
    return view('encargado.dashboard');
})->middleware(['auth', 'role:encargado'])->name('encargado.dashboard');

// Almacén: productos, entradas y salidas
Route::middleware(['auth', 'role:encargado,dueno,administrador'])->prefix('almacen')->group(function () {
    Route::get('/', function () { return redirect('/almacen/dashboard'); });
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'almacen'])->name('almacen.dashboard');

    // Productos (CRUD minimal)
    Route::get('/productos', [\App\Http\Controllers\Admin\AlmacenController::class, 'index'])->name('almacen.productos');
    Route::get('/productos/create', [\App\Http\Controllers\AlmacenController::class, 'create'])->name('almacen.productos.create');
    Route::post('/productos', [\App\Http\Controllers\AlmacenController::class, 'store'])->name('almacen.productos.store');
    Route::get('/productos/{producto}', [\App\Http\Controllers\AlmacenController::class, 'show'])->name('almacen.productos.show');
    Route::get('/productos/{producto}/edit', [\App\Http\Controllers\AlmacenController::class, 'edit'])->name('almacen.productos.edit');
    Route::put('/productos/{producto}', [\App\Http\Controllers\AlmacenController::class, 'update'])->name('almacen.productos.update');
    Route::delete('/productos/{producto}', [\App\Http\Controllers\AlmacenController::class, 'destroy'])->name('almacen.productos.destroy');

    // Entradas
    Route::get('/entradas', function(){ $movimientos = \App\Models\EntradaInventario::with('producto','proveedor')->latest()->limit(50)->get(); return view('almacen.entradas', compact('movimientos')); })->name('almacen.entradas');
    Route::post('/entradas', [\App\Http\Controllers\EntradasController::class, 'store'])->name('almacen.entradas.store');

    // Salidas
    Route::get('/salidas', function(){ $movimientos = \App\Models\SalidaInventario::with('producto')->latest()->limit(50)->get(); return view('almacen.salidas', compact('movimientos')); })->name('almacen.salidas');
    Route::post('/salidas', [\App\Http\Controllers\SalidasController::class, 'store'])->name('almacen.salidas.store');
});

// Cajero routes: panel + ventas
Route::middleware(['auth', 'role:cajero'])->prefix('cajero')->group(function () {
    Route::get('/', function () {
        return view('cajero.dashboard');
    })->name('cajero.dashboard');

    // Control de Caja y Turnos
    Route::get('/caja', [\App\Http\Controllers\CajaController::class, 'index'])->name('cajero.caja.index');
    Route::post('/caja/abrir', [\App\Http\Controllers\CajaController::class, 'abrir'])->name('cajero.caja.abrir');
    Route::post('/caja/gasto', [\App\Http\Controllers\CajaController::class, 'registrarGasto'])->name('cajero.caja.gasto');
    Route::post('/caja/cerrar', [\App\Http\Controllers\CajaController::class, 'cerrar'])->name('cajero.caja.cerrar');

    // Punto de venta para cajeros (reutiliza el controlador de Ventas)
    Route::get('/ventas', [VentasController::class, 'index'])->name('cajero.ventas.index');
    Route::post('/ventas/search', [VentasController::class, 'search'])->name('cajero.ventas.search');
    Route::post('/ventas/store', [VentasController::class, 'store'])->name('cajero.ventas.store');
});
