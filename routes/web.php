<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AlmacenController;
use App\Http\Controllers\Admin\ProveedoresController;
use App\Http\Controllers\VentasController;
use App\Http\Controllers\Admin\UsuariosController;
use App\Http\Controllers\Admin\ReportesController;

Route::get('/', function () {
    return view('welcome');
});

// Ruta temporal ver su diseño Frontend
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
// Rutas de autenticación
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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
    });
});

// Ventas: movida bajo /admin y accesible para el Dueño
Route::middleware(['auth', 'role:dueno'])->prefix('admin')->group(function () {
    Route::get('/ventas', [VentasController::class, 'index'])->name('admin.ventas.index');
    // Usuarios - acceso exclusivo del Dueño
    Route::get('/usuarios', [UsuariosController::class, 'index'])->name('admin.usuarios.index');
    // Reportes - panel de inteligencia (Dueño)
    Route::get('/reportes', [ReportesController::class, 'index'])->name('admin.reportes.index');
});
Route::get('/encargado/dashboard', function () {
    return view('encargado.dashboard');
})->middleware(['auth', 'role:encargado'])->name('encargado.dashboard');

// Cajero routes: panel + ventas
Route::middleware(['auth', 'role:cajero'])->prefix('cajero')->group(function () {
    Route::get('/', function () {
        return view('cajero.dashboard');
    })->name('cajero.dashboard');

    // Punto de venta para cajeros (reutiliza el controlador de Ventas)
    Route::get('/ventas', [VentasController::class, 'index'])->name('cajero.ventas.index');
});
