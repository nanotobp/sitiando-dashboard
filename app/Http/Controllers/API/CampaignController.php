<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers;
use App\Models\Campaign;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    /**
     * Listar todas las campañas activas
     */
    public function index()
    {
        $campaigns = Campaign::orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $campaigns
        ]);
    }

    /**
     * Mostrar una campaña específica
     */
    public function show($id)
    {
        $campaign = Campaign::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $campaign
        ]);
    }
}
