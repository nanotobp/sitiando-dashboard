<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\AffiliateDashboardService;
use App\Models\Affiliate;
use Illuminate\Http\Request;

class AffiliateDashboardController extends Controller
{
    protected $service;

    public function __construct(AffiliateDashboardService $service)
    {
        $this->service = $service;
    }

    public function me(Request $request)
    {
        // email viene del SupabaseAuth Middleware
        $email = $request->user()->email;

        $affiliate = Affiliate::where('email', $email)->first();

        if (!$affiliate) {
            return response()->json([
                'success' => false,
                'message' => 'Affiliate not found',
            ], 404);
        }

        $dashboard = $this->service->getDashboard($affiliate);

        return response()->json([
            'success' => true,
            'data' => $dashboard,
        ]);
    }
}
