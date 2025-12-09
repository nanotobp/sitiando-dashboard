<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AffiliatePayoutController;
use App\Http\Controllers\Admin\AffiliateAnalyticsController;
use App\Http\Controllers\Admin\AffiliateAnalyticsDetailController;
use App\Http\Controllers\CartController;


/*
|--------------------------------------------------------------------------
| ADMIN — RUTAS PROTEGIDAS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /* DASHBOARD */
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        /* ANALYTICS AFILIADOS */
        Route::get('/analytics/affiliates',
            [AffiliateAnalyticsController::class, 'index']
        )->name('analytics.affiliates');

        Route::get('/analytics/affiliates/{id}',
            [AffiliateAnalyticsDetailController::class, 'show']
        )->name('analytics.affiliates.show');

        /* USUARIOS */
        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        Route::get('/users/{user}', [UserController::class, 'show'])
            ->name('users.show');

        /* ROLES */
        Route::get('/roles', [RoleController::class, 'index'])
            ->name('roles.index');

        Route::get('/roles/{id}', [RoleController::class, 'show'])
            ->name('roles.show');

        /* PAYOUTS */
        Route::get('/payouts', [AffiliatePayoutController::class, 'index'])
            ->name('payouts.index');

        /* CARRITOS */
        Route::get('/carts', [\App\Http\Controllers\Admin\CartController::class, 'index'])
            ->name('carts.index');

    
        /* ÓRDENES */
Route::middleware(['roles:admin,manager,seller'])->group(function () {

    Route::get('/orders', [OrderController::class, 'index'])
        ->name('orders.index');

    Route::get('/orders/{order}', [OrderController::class, 'show'])
        ->name('orders.show');

    Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])
        ->name('orders.update-status');
});


        /* PRODUCTOS */
        Route::get('/products', [ProductController::class, 'index'])
            ->name('products.index');

    });


/*
|--------------------------------------------------------------------------
| FRONTEND CART
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/cart', [CartController::class, 'index'])
        ->name('cart.index');
});


/*
|--------------------------------------------------------------------------
| LOGIN / LOGOUT
|--------------------------------------------------------------------------
*/
Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| HOME REDIRECT
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('admin.dashboard'));
