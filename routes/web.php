<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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
use App\Http\Controllers\DashboardController;

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
Route::middleware('auth')->group(function () {
    Route::resource('productos', ProductController::class);
});

Route::get('/force-rollback', function () {
    Artisan::call('migrate:rollback --force');
    Artisan::call('migrate:rollback --force');
    Artisan::call('migrate:rollback --force');
    Artisan::call('migrate:rollback --force');
    Artisan::call('migrate:rollback --force');
    return "Rollback ejecutado varias veces.";
});
Route::get('/drop-users', function () {
    Schema::dropIfExists('users');
    return "users eliminada";
});
