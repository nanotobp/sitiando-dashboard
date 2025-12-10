<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * El namespace que se usará para los controladores.
     * (Opcional, pero útil para evitar escribir App\Http\Controllers cada vez)
     */
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Aquí registramos los enlaces de modelos, patrones y rutas.
     */
    public function boot(): void
    {
        parent::boot();

        $this->routes(function () {

            /**
             * ==========================================================
             *  RUTAS WEB (frontend + admin)
             * ==========================================================
             */
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            /**
             * ==========================================================
             *  RUTAS API
             * ==========================================================
             */
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            /**
             * ==========================================================
             * OPCIONAL — RUTAS DE BACKOFFICE SEPARADAS
             * Mantiene limpio el web.php
             * ==========================================================
             */
            if (file_exists(base_path('routes/admin.php'))) {
                Route::middleware(['web', 'auth'])
                    ->prefix('admin')
                    ->as('admin.')
                    ->namespace($this->namespace . '\\Admin')
                    ->group(base_path('routes/admin.php'));
            }
        });
    }
}
