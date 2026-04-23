<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Ruta temporal ver su diseño Frontend
Route::view('/login', 'auth.login');