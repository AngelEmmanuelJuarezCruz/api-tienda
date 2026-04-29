<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect()->route('login.form');
});

Route::get('/login', [DashboardController::class, 'loginForm'])->name('login.form');

// Rutas de autenticación
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Grupos de rutas protegidos por inicio de sesión (auth) y perfil de seguridad (role)
Route::middleware(['auth', 'role:dueno,administrador'])->prefix('admin')->group(function () {
    Route::get('/', function () {
        return redirect('/admin/dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
});

Route::middleware(['auth', 'role:encargado'])->prefix('almacen')->group(function () {
    Route::get('/', function () {
        return redirect('/almacen/dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'almacen'])->name('almacen.dashboard');
    Route::get('/productos', [DashboardController::class, 'productos'])->name('almacen.productos');
});

Route::middleware(['auth', 'role:cajero'])->prefix('ventas')->group(function () {
    Route::get('/', function () {
        return redirect('/ventas/dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'ventas'])->name('ventas.dashboard');
});
