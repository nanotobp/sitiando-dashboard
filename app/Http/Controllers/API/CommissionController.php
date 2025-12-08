<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\CommissionService;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    protected $service;

    public function __construct(CommissionService $service)
    {
        $this->service = $service;
    }

    /**
     * Generar comisiones para los afiliados
     */
    public function generate(Request $request)
    {
        $result = $this->service->generateCommissions();

        return response()->json([
            'success' => true,
            'message' => 'Comisiones generadas correctamente.',
            'data' => $result,
        ]);
    }
}
