<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\TrackingService;
use App\Services\AffiliateService;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    protected $tracking;
    protected $affiliates;

    public function __construct(TrackingService $tracking, AffiliateService $affiliates)
    {
        $this->tracking = $tracking;
        $this->affiliates = $affiliates;
    }

    /**
     * Registrar CLIC desde Cloudflare Worker
     */
    public function trackClick(Request $request)
    {
        $referral = $request->get('ref');
        $affiliate = $this->affiliates->findByReferralCode($referral);

        if (!$affiliate) {
            return response()->json([
                'success' => false,
                'message' => 'Affiliate not found'
            ], 404);
        }

        $click = $this->tracking->logClick(array_merge($request->all(), [
            'affiliate_id' => $affiliate->id,
            'referral_code' => $affiliate->referral_code
        ]));

        return response()->json([
            'success' => true,
            'data' => $click
        ]);
    }
}
