<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Aquí puedes agregar middlewares globales o grupos personalizados
        // ej: $middleware->append(\App\Http\Middleware\MyMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Manejo global de excepciones
        // ej: $exceptions->render(function(Exception $e, $request) { ... });
    })
    ->create();
