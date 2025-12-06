<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;

// Home
Route::get('/', function () {
    return view('welcome');
});

// Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// Dashboard protegido
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('productos', ProductController::class);
});

// Utilidades de mantenimiento
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

// 🚀 ESTA ES LA QUE FALTABA
Route::get('/fix-migrate', function () {
    Artisan::call('migrate:fresh --force');
    return "Migraciones reseteadas y recreadas.";
});
