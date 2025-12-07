<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AffiliateController;
use App\Http\Controllers\Admin\AffiliatePayoutController;
use App\Http\Controllers\Admin\AffiliateAnalyticsController;
use App\Http\Controllers\Admin\AffiliateAnalyticsDetailController;
use App\Http\Controllers\CartController;


/*
|--------------------------------------------------------------------------
| Web Routes — Sitiando Ecommerce PRO
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // ======================================
    // DASHBOARD
    // ======================================
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

Route::middleware(['ability:view_affiliate_performance'])->group(function () {

    Route::get('/analytics/affiliates', 
        [AffiliateAnalyticsController::class, 'index']
    )->name('analytics.affiliates');

});
Route::middleware(['role:admin'])->group(function () {

    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');

    Route::get('/users/{id}', [UserController::class, 'show'])->name('admin.users.show');
});


Route::middleware(['ability:view_affiliate_performance'])->group(function () {
    Route::get('/analytics/affiliates/{id}', 
        [AffiliateAnalyticsDetailController::class, 'show']
    )->name('analytics.affiliates.show');
});

Route::middleware(['roles:admin,manager'])->group(function () {

    Route::get('/carts', [\App\Http\Controllers\Admin\CartController::class, 'index'])
        ->name('carts.index');

    Route::get('/carts/{id}', [\App\Http\Controllers\Admin\CartController::class, 'show'])
        ->name('carts.show');
});

    // ======================================
    // AFILIADOS / VENDEDORES EXTERNOS
    // ======================================
    Route::middleware(['ability:view_affiliates'])->group(function () {

        Route::get('/affiliates', [AffiliateController::class, 'index'])
            ->name('affiliates.index');

        Route::get('/affiliates/{id}', [AffiliateController::class, 'show'])
            ->name('affiliates.show');
    });


    // ======================================
    // LIQUIDACIONES (PAYOUTS)
    // ======================================
    Route::middleware(['ability:manage_commissions'])->group(function () {

        Route::get('/payouts', [AffiliatePayoutController::class, 'index'])
            ->name('payouts.index');

        Route::get('/payouts/create', [AffiliatePayoutController::class, 'create'])
            ->name('payouts.create');

        Route::post('/payouts', [AffiliatePayoutController::class, 'store'])
            ->name('payouts.store');

        Route::get('/payouts/{id}', [AffiliatePayoutController::class, 'show'])
            ->name('payouts.show');

        Route::post('/payouts/{id}/status', [AffiliatePayoutController::class, 'updateStatus'])
            ->name('payouts.updateStatus');

        Route::post('/payouts/{id}/proof', [AffiliatePayoutController::class, 'uploadProof'])
            ->name('payouts.uploadProof');

        // EXPORTACIONES
        Route::get('/payouts/{id}/export/csv', [AffiliatePayoutController::class, 'exportCsv'])
            ->name('payouts.export.csv');

        Route::get('/payouts/{id}/export/commissions', [AffiliatePayoutController::class, 'exportCommissionsCsv'])
            ->name('payouts.export.commissions');
    });


    // ======================================
    // ÓRDENES / VENTAS
    // ======================================
    Route::middleware(['roles:admin,manager,seller'])->group(function () {

        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::post('/orders/{order}/payment', [OrderController::class, 'registerPayment'])->name('orders.register-payment');
        Route::post('/orders/{order}/resend-payment-link', [OrderController::class, 'resendPaymentLink'])->name('orders.resend-payment-link');
    });


    // ======================================
    // PRODUCTOS CRUD
    // ======================================
    Route::middleware(['roles:admin,manager'])->group(function () {

        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });


    // ======================================
    // USUARIOS DEL SISTEMA
    // ======================================
    Route::middleware(['role:admin'])->group(function () {

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    });


    // ======================================
    // ROLES Y PERMISOS (ACL PRO)
    // ======================================
    Route::middleware(['ability:view_roles'])->group(function () {

        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/{id}', [RoleController::class, 'show'])->name('roles.show');

        Route::post('/roles/{id}/abilities', [RoleController::class, 'updateAbilities'])
            ->middleware('ability:edit_permissions')
            ->name('roles.updateAbilities');
    });


    // ======================================
    // EDITAR ROLES DE USUARIOS
    // ======================================
    Route::middleware(['ability:edit_users'])->group(function () {

        Route::get('/users/{id}/role', [UserController::class, 'editRole'])
            ->name('users.editRole');

        Route::post('/users/{id}/role', [UserController::class, 'updateRole'])
            ->name('users.updateRole');

        Route::get('/users/{id}/permissions', [UserController::class, 'permissions'])
            ->name('users.permissions');
    });

});
// ======================================
// CARRITO PÚBLICO (MÓDULO 1)
// ======================================
Route::middleware(['auth'])->group(function () {

    // Ver carrito
    Route::get('/cart', [CartController::class, 'index'])
        ->name('cart.index');

    // Agregar producto al carrito
    Route::post('/cart/add', [CartController::class, 'add'])
        ->name('cart.add');

    // Actualizar cantidad de un ítem
    Route::put('/cart/items/{item}', [CartController::class, 'updateItem'])
        ->name('cart.item.update');

    // Eliminar ítem
    Route::delete('/cart/items/{item}', [CartController::class, 'removeItem'])
        ->name('cart.item.remove');

    // Vaciar carrito
    Route::delete('/cart', [CartController::class, 'clear'])
        ->name('cart.clear');
});

// ======================================
// CHECKOUT PRO
// ======================================
Route::middleware(['auth'])->group(function () {

    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])
        ->name('checkout.index');

    Route::post('/checkout/process', [\App\Http\Controllers\CheckoutController::class, 'process'])
        ->name('checkout.process');

    Route::get('/checkout/success/{id}', [\App\Http\Controllers\CheckoutController::class, 'success'])
        ->name('checkout.success');
});
// =======================
// PAGOS ONLINE PRO
// =======================
Route::get('/payment/pay/{orderId}', [\App\Http\Controllers\PaymentController::class, 'pay'])
    ->name('payment.pay');

Route::get('/payment/return', [\App\Http\Controllers\PaymentController::class, 'paymentReturn'])
    ->name('payment.return');

Route::post('/payment/notify', [\App\Http\Controllers\PaymentController::class, 'notify'])
    ->name('payment.notify');

Route::get('/payment/cancel', [\App\Http\Controllers\PaymentController::class, 'cancel'])
    ->name('payment.cancel');

// ======================================
// REDIRECCIÓN PRINCIPAL
// ======================================
Route::get('/', fn () => redirect()->route('admin.dashboard'));


// ======================================
// FALLBACK
// ======================================
Route::fallback(fn () => redirect()->route('admin.dashboard'));
