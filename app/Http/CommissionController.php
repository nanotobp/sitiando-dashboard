<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\CommissionService;
use App\Models\AffiliateClick;
use App\Models\Order;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    protected $service;

    public function __construct(CommissionService $service)
    {
        $this->service = $service;
    }

    /**
     * Generar comisión después de una compra
     */
    public function generate(Request $request)
    {
        $click = AffiliateClick::findOrFail($request->click_id);
        $order = Order::findOrFail($request->order_id);

        $commission = $this->service->generateCommission($click, $order);

        return response()->json([
            'success' => true,
            'data' => $commission
        ]);
    }
}
