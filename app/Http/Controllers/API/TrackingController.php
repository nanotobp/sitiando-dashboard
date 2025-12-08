<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Affiliate;
use App\Models\AffiliateClick;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class TrackingController extends Controller
{
    /**
     * 🔥 TRACKING WEB NORMAL
     * Frontend → /api/track
     */
    public function trackClick(Request $request)
    {
        return $this->registerClick($request, 'web');
    }

    /**
     * ⚡ TRACKING EDGE
     * Cloudflare Worker → /api/affiliates/track-click-edge
     */
    public function trackClickEdge(Request $request)
    {
        if ($request->secret !== env('TRACKING_SECRET')) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid secret',
            ], 403);
        }

        return $this->registerClick($request, 'edge');
    }

    /**
     * ⭐ LÓGICA CENTRAL DE TRACKING COMPARTIDA (WEB + EDGE)
     */
    private function registerClick(Request $request, string $source)
    {
        $ref = $request->ref;

        if (!$ref) {
            return response()->json([
                'success' => false,
                'message' => 'Missing ref',
            ], 400);
        }

        $affiliate = Affiliate::where('code', $ref)->first();

        if (!$affiliate) {
            return response()->json([
                'success' => false,
                'message' => 'Affiliate not found',
            ], 404);
        }

        // Anti-spam
        $fp = $request->fingerprint ?? Str::uuid()->toString();
        $cacheKey = "click-limit:{$affiliate->id}:{$fp}";

        if (Cache::has($cacheKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Rate limited',
            ], 429);
        }

        Cache::put($cacheKey, true, now()->addSeconds(5));

        // Anonimizar IP
        $ip = $request->ip() ?? $request->header('CF-Connecting-IP');
        $ipHash = hash('sha256', $ip . env('APP_KEY'));

        // Guardar click
        AffiliateClick::create([
            'affiliate_id' => $affiliate->id,
            'product_id'   => $request->product_id,
            'campaign_id'  => $request->campaign_id,
            'referrer'     => $request->referrer,
            'ip'           => $ipHash,
            'ua'           => $request->userAgent(),
            'fingerprint'  => $fp,
            'utm_source'   => $request->utm_source,
            'utm_medium'   => $request->utm_medium,
            'utm_campaign' => $request->utm_campaign,
            'country'      => $request->country,
            'city'         => $request->city,
            'landing'      => $request->landing,
            'source'       => $source,
        ]);

        return response()->json(['success' => true]);
    }
}
