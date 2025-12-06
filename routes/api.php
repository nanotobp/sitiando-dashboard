<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AffiliateController;
use App\Http\Controllers\API\TrackingController;
use App\Http\Controllers\API\CommissionController;
use App\Http\Controllers\API\PayoutController;
use App\Http\Controllers\API\MediaKitController;
use App\Http\Controllers\API\CampaignController;
use App\Http\Controllers\API\AffiliateDashboardController;
use App\Http\Controllers\API\TrackingController;




/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Acá definimos las rutas de la API de Sitiando.
| Algunas son públicas (tracking) y otras requieren Supabase Auth.
|
*/

// ✅ Ruta simple para probar que el backend responde
Route::get('/ping', function () {
    return response()->json(['pong' => true]);
});

// ✅ Endpoint que puede usar Cloudflare Worker para registrar clics
//    (si querés, después le agregamos un token interno o firma)
Route::post('/track', [TrackingController::class, 'trackClick']);

// 🔐 RUTAS PROTEGIDAS POR SUPABASE AUTH
Route::middleware('supabase.auth')->group(function () {

    // Datos básicos del usuario autenticado (debug + útil para frontend)
    Route::get('/me', function (Request $request) {
        return response()->json([
            'success'  => true,
            'user'     => $request->user(),
            'supabase' => $request->attributes->get('supabase_payload'),
        ]);
    });

    // Afiliados
    Route::prefix('affiliates')->group(function () {
        Route::get('/', [AffiliateController::class, 'index']);
        Route::post('/', [AffiliateController::class, 'store']);
        Route::get('/{id}', [AffiliateController::class, 'show']);
        Route::get('/affiliates/me/dashboard', [AffiliateDashboardController::class, 'me']);
        Route::post('/affiliates/track-click-edge', [TrackingController::class, 'trackClickEdge']);

    });


    // Comisiones
    Route::post('/commissions/generate', [CommissionController::class, 'generate']);

    // Payouts
    Route::post('/payouts/generate', [PayoutController::class, 'generate']);

    // Media Kit
    Route::get('/media-kit', [MediaKitController::class, 'index']);
    Route::get('/media-kit/{id}/download', [MediaKitController::class, 'download']);

    // Campañas
    Route::get('/campaigns', [CampaignController::class, 'index']);
    Route::get('/campaigns/{id}', [CampaignController::class, 'show']);
});
