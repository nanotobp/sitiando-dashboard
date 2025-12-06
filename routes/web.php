<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/fix-migrate', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate:fresh --seed --force');
    return "Migraciones reseteadas y seeders ejecutados.";
});
