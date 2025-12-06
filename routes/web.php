Route::get('/fix-migrate', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate:fresh --seed --force');
    return "Migraciones reseteadas y seeders ejecutados.";
});
