<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\CampaignService;
use App\Models\AffiliateCampaign;

class CampaignController extends Controller
{
    protected $service;

    public function __construct(CampaignService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => AffiliateCampaign::all()
        ]);
    }

    public function show($id)
    {
        $campaign = AffiliateCampaign::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $campaign
        ]);
    }
}
