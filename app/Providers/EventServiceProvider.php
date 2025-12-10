<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        /**
         * ==========================================================
         *  EVENTOS DE AUTENTICACIÓN
         * ==========================================================
         */
        Login::class => [
            // \App\Listeners\LogLoginActivity::class,
        ],

        Logout::class => [
            // \App\Listeners\LogLogoutActivity::class,
        ],

        /**
         * ==========================================================
         *  EVENTOS DE ECOMMERCE (SUGERIDOS)
         * ==========================================================
         */

        // Cuando se crea una orden
        // \App\Events\OrderCreated::class => [
        //     \App\Listeners\RecordOrderCreated::class,
        // ],

        // Cuando cambia el estado de la orden
        // \App\Events\OrderStatusChanged::class => [
        //     \App\Listeners\HandleOrderStatus::class,
        // ],

        // Pago aprobado
        // \App\Events\PaymentApproved::class => [
        //     \App\Listeners\NotifyPaymentApproved::class,
        // ],

        /**
         * ==========================================================
         *  EVENTOS DE AFILIADOS (SITiANDO PRO)
         * ==========================================================
         */

        // click detectado
        // \App\Events\AffiliateClickCreated::class => [
        //     \App\Listeners\ProcessClickFraudDetection::class,
        // ],

        // venta convertida
        // \App\Events\AffiliateConversionCreated::class => [
        //     \App\Listeners\CalculateCommission::class,
        //     \App\Listeners\UpdateAffiliateStats::class,
        // ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();

        /**
         * Aquí podemos registrar eventos manuales,
         * listeners anónimos o channel routing.
         */
    }
}
