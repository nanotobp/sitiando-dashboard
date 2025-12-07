<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;


/*
|--------------------------------------------------------------------------
| Web Routes — Sitiando Ecommerce PRO
|--------------------------------------------------------------------------
| Versión PRO organizada con:
| - prefix /admin
| - middleware auth
| - middleware de roles
| - nombres de rutas admin.*
|--------------------------------------------------------------------------
*/


// ======================================
// PANEL ADMIN (PROTECTED)
// ======================================
Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // ==========================
    // DASHBOARD (todos los roles)
    // ==========================
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');


    // ==========================
    // ÓRDENES / VENTAS
    // Roles permitidos: admin, manager, seller
    // ==========================
    Route::middleware(['roles:admin,manager,seller'])->group(function () {

        Route::get('/orders', [OrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/orders/{order}', [OrderController::class, 'show'])
            ->name('orders.show');

        Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])
            ->name('orders.update-status');

        Route::post('/orders/{order}/payment', [OrderController::class, 'registerPayment'])
            ->name('orders.register-payment');

        Route::post('/orders/{order}/resend-payment-link', [OrderController::class, 'resendPaymentLink'])
            ->name('orders.resend-payment-link');
    });


    // ==========================
    // PRODUCTOS CRUD
    // Roles permitidos: admin, manager
    // ==========================
    Route::middleware(['roles:admin,manager'])->group(function () {

        Route::get('/products', [ProductController::class, 'index'])
            ->name('products.index');

        Route::get('/products/create', [ProductController::class, 'create'])
            ->name('products.create');

        Route::post('/products', [ProductController::class, 'store'])
            ->name('products.store');

        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
            ->name('products.edit');

        Route::put('/products/{product}', [ProductController::class, 'update'])
            ->name('products.update');

        Route::delete('/products/{product}', [ProductController::class, 'destroy'])
            ->name('products.destroy');
    });


    // ==========================
    // USUARIOS DEL SISTEMA
    // Solo Admin
    // ==========================
    Route::middleware(['role:admin'])->group(function () {

        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        Route::get('/users/{user}', [UserController::class, 'show'])
            ->name('users.show');
    });

});



// ======================================
// REDIRECCIÓN PRINCIPAL
// ======================================
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});


// ======================================
// FALLBACK
// ======================================
Route::fallback(function () {
    return redirect()->route('admin.dashboard');
});
