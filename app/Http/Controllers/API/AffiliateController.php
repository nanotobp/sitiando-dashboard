<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers;
use App\Services\AffiliateService;
use Illuminate\Http\Request;

class AffiliateController extends Controller
{
    protected $service;

    public function __construct(AffiliateService $service)
    {
        $this->service = $service;
    }

    /**
     * Listar afiliados
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => \App\Models\Affiliate::all()
        ]);
    }

    /**
     * Crear afiliado
     */
    public function store(Request $request)
    {
        $affiliate = $this->service->create($request->all());

        return response()->json([
            'success' => true,
            'data' => $affiliate
        ], 201);
    }

    /**
     * Mostrar afiliado
     */
    public function show($id)
    {
        $affiliate = \App\Models\Affiliate::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $affiliate
        ]);
    }
}
