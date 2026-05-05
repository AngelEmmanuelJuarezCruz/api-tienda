<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

// Rutas de autenticación
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Grupos de rutas protegidos por inicio de sesión (auth) y perfil de seguridad (role)
Route::middleware(['auth', 'role:dueño,administrador'])->prefix('admin')->group(function () {
    Route::get('/', function () {
        return 'Dashboard Dueño / Admin';
    });
});

Route::middleware(['auth', 'role:encargado'])->prefix('almacen')->group(function () {
    Route::get('/', function () {
        return 'Panel de Control de Almacén';
    });

    Route::get('/dashboard', [DashboardController::class, 'almacen'])->name('almacen.dashboard');
    Route::get('/productos', [DashboardController::class, 'productos'])->name('almacen.productos');
});

Route::middleware(['auth', 'role:cajero'])->prefix('ventas')->group(function () {
    Route::get('/', function () {
        return 'Módulo de Gestión de Ventas';
    });

    Route::get('/dashboard', [DashboardController::class, 'ventas'])->name('ventas.dashboard');
});
