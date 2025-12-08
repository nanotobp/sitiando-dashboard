<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AffiliateController;
use App\Http\Controllers\API\CommissionController;
use App\Http\Controllers\API\PayoutController;
use App\Http\Controllers\API\MediaKitController;
use App\Http\Controllers\API\CampaignController;
use App\Http\Controllers\API\AffiliateDashboardController;
use App\Http\Controllers\API\TrackingController;

/*
|--------------------------------------------------------------------------
| API Routes — Sitiando PRO
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| 🔓 Rutas públicas
|--------------------------------------------------------------------------
*/

Route::get('/ping', fn () => response()->json(['pong' => true]));

// Tracking
Route::post('/track', [TrackingController::class, 'trackClick']);
Route::post('/track-edge', [TrackingController::class, 'trackClickEdge']);


/*
|--------------------------------------------------------------------------
| 🔐 Rutas protegidas por Supabase Auth
|--------------------------------------------------------------------------
*/
Route::middleware('supabase.auth')->group(function () {

    // Usuario autenticado
    Route::get('/me', function (Request $request) {
        return response()->json([
            'success'  => true,
            'user'     => $request->user(),
            'supabase' => $request->attributes->get('supabase_payload'),
        ]);
    });

    /*
    |--------------------------------------------------------------------------
    | AFFILIATES
    |--------------------------------------------------------------------------
    */
    Route::prefix('affiliates')->group(function () {

        Route::get('/', [AffiliateController::class, 'index']);
        Route::post('/', [AffiliateController::class, 'store']);
        Route::get('/{id}', [AffiliateController::class, 'show']);

        // Dashboard personal
        Route::get('/me/dashboard', [AffiliateDashboardController::class, 'me']);
    });


    /*
    |--------------------------------------------------------------------------
    | COMISIONES
    |--------------------------------------------------------------------------
    */
    Route::post('/commissions/generate', [CommissionController::class, 'generate']);


    /*
    |--------------------------------------------------------------------------
    | PAYOUTS
    |--------------------------------------------------------------------------
    */
    Route::post('/payouts/generate', [PayoutController::class, 'generate']);


    /*
    |--------------------------------------------------------------------------
    | MEDIA KIT
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
