<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Affiliate;
use App\Models\AffiliateClick;

class TrackingController extends Controller
{
    public function trackClickEdge(Request $request)
    {
        if ($request->secret !== env('TRACKING_SECRET')) {
            return response()->json(['success' => false, 'message' => 'Invalid secret'], 403);
        }

        $ref = $request->ref;

        if (!$ref) {
            return response()->json(['success' => false, 'message' => 'Missing ref'], 400);
        }

        $affiliate = Affiliate::where('code', $ref)->first();

        if (!$affiliate) {
            return response()->json(['success' => false, 'message' => 'Affiliate not found'], 404);
        }

        AffiliateClick::create([
            'affiliate_id' => $affiliate->id,
            'product_id' => $request->product_id,
            'referrer' => $request->referrer,
            'ip' => $request->ip,
            'ua' => $request->ua,
            'fingerprint' => $request->fingerprint,
            'utm_source' => $request->utm_source,
            'utm_medium' => $request->utm_medium,
            'utm_campaign' => $request->utm_campaign,
            'country' => $request->country,
            'city' => $request->city,
            'landing' => $request->landing,
        ]);

        return response()->json(['success' => true]);
    }
}
