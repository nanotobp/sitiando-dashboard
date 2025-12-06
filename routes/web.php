<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/fix-migrate', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate:fresh --seed --force');
    return "Migraciones reseteadas y seeders ejecutados.";
});
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// Rutas protegidas:
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return "Bienvenido al Dashboard de Sitiando";
    });
});
