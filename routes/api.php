<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AffiliateController;
//use App\Http\Controllers\API\TrackingController;
use App\Http\Controllers\API\CommissionController;
use App\Http\Controllers\API\PayoutController;
use App\Http\Controllers\API\MediaKitController;
use App\Http\Controllers\API\CampaignController;
use App\Http\Controllers\API\AffiliateDashboardController;

/*
|--------------------------------------------------------------------------
| API Routes — Sitiando PRO
|--------------------------------------------------------------------------
| Acá definimos las rutas de la API de Sitiando.
| Algunas son públicas (tracking) y otras requieren Supabase Auth.
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| 🔓 RUTAS PÚBLICAS
|--------------------------------------------------------------------------
*/

// Ping test
Route::get('/ping', fn () => response()->json(['pong' => true]));

// Tracking WEB (frontend normal)
//Route::post('/track', [TrackingController::class, 'trackClick']);

// Tracking desde Cloudflare Worker (usa secret)
// Esta ruta es suficiente y no necesitamos la versión anidada en 'affiliates'.
//Route::post('/track-edge', [TrackingController::class, 'trackClickEdge']);


/*
|--------------------------------------------------------------------------
| 🔐 RUTAS PROTEGIDAS POR SUPABASE AUTH
|--------------------------------------------------------------------------
*/
Route::middleware('supabase.auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Usuario autenticado (debug + frontend)
    |--------------------------------------------------------------------------
    */
    Route::get('/me', function (Request $request) {
        return response()->json([
            'success'  => true,
            'user'     => $request->user(),
            'supabase' => $request->attributes->get('supabase_payload'),
        ]);
    });


    /*
    |--------------------------------------------------------------------------
    | AFFILIATES (CRUD + Dashboard)
    |--------------------------------------------------------------------------
    */
    Route::prefix('affiliates')->group(function () {

        Route::get('/', [AffiliateController::class, 'index']);
        Route::post('/', [AffiliateController::class, 'store']);
        Route::get('/{id}', [AffiliateController::class, 'show']);

        // Dashboard personal del afiliado
        Route::get('/me/dashboard', [AffiliateDashboardController::class, 'me']);

        // Tracking EDGE interno: Se ha ELIMINADO la ruta duplicada aquí.
        // Route::post('/track-click-edge', [TrackingController::class, 'trackClickEdge']); 
    });


    /*
    |--------------------------------------------------------------------------
    | COMISIONES
    |--------------------------------------------------------------------------
    */
    Route::post('/commissions/generate', [CommissionController::class, 'generate']);


    /*
    |--------------------------------------------------------------------------
    | PAYOUTS (liquidaciones)
    |--------------------------------------------------------------------------
    */
    Route::post('/payouts/generate', [PayoutController::class, 'generate']);


    /*
    |--------------------------------------------------------------------------
    | MEDIA KIT (descargas)
    |--------------------------------------------------------------------------
    */
    Route::get('/media-kit', [MediaKitController::class, 'index']);
    Route::get('/media-kit/{id}/download', [MediaKitController::class, 'download']);


    /*
    |--------------------------------------------------------------------------
    | CAMPAÑAS
    |--------------------------------------------------------------------------
    */
    Route::get('/campaigns', [CampaignController::class, 'index']);
    Route::get('/campaigns/{id}', [CampaignController::class, 'show']);
});