<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DespachoController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;

// Redirigir raíz al login
Route::get('/', fn () => redirect()->route('login'));

// Autenticación (solo para invitados)
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Rutas protegidas
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    // Despachos
    Route::get('/despachos/exportar',          [DespachoController::class, 'exportar'])->name('despachos.exportar');
    Route::get('/despachos/{despacho}/exportar', [DespachoController::class, 'exportarUno'])->name('despachos.exportar-uno');
    Route::get('/despachos/{despacho}/adjunto',  [DespachoController::class, 'descargarAdjunto'])->name('despachos.adjunto');
    Route::resource('despachos', DespachoController::class);

    // Catálogos
    Route::resource('clientes', ClienteController::class)->except(['show']);
    Route::resource('productos', ProductoController::class)->except(['show']);

    // API interna para autocompletar producto
    Route::get('/api/productos/{producto}', [ProductoController::class, 'apiShow'])->name('api.productos.show');
});
