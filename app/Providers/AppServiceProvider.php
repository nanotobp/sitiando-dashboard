<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        /**
         * Cargar helpers globales (si existe helpers.php)
         */
        $helpers = base_path('app/helpers.php');
        if (file_exists($helpers)) {
            require_once $helpers;
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * Forzar HTTPS en producción
         * (Railway / Cloudflare / proxies que rompen el esquema)
         */
        if (config('app.env') === 'production') {
            URL::forceScheme('https');

            // Fix para proxies Heroku / Railway / Cloudflare
            if (
                isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
                $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https'
            ) {
                URL::forceScheme('https');
            }
        }

        /**
         * Fix para Postgres: evitar errores por índices largos.
         * PG casi nunca rompe por esto, pero es seguro dejarlo.
         */
        Schema::defaultStringLength(191);

        /**
         * Usar Bootstrap 5 en los paginadores por defecto
         */
        Paginator::useBootstrapFive();

        /**
         * Zona horaria correcta
         */
        date_default_timezone_set(
            config('app.timezone', 'America/Asuncion')
        );

        /**
         * Cache busting automático para assets
         * Ejemplo desde Blade:
         * <link rel="stylesheet" href="{{ mix_asset('css/dashboard.css') }}">
         */
        if (!function_exists('mix_asset')) {
            function mix_asset($path)
            {
                $file = public_path($path);
                $version = file_exists($file) ? filemtime($file) : time();
                return asset($path) . '?v=' . $version;
            }
        }
    }
}
