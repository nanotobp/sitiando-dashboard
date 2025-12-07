<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers;
use App\Services\PayoutService;
use App\Models\Affiliate;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    protected $service;

    public function __construct(PayoutService $service)
    {
        $this->service = $service;
    }

    /**
     * Generar payout para un afiliado
     */
    public function generate(Request $request)
    {
        $affiliate = Affiliate::findOrFail($request->affiliate_id);

        $payout = $this->service->generatePayout($affiliate);

        return response()->json([
            'success' => true,
            'data' => $payout
        ]);
    }
}
