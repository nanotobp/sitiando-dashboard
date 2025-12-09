<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\VentasService;

class VentasController extends Controller
{
    /**
     * Listado de ventas con filtros
     */
    public function index(Request $request, VentasService $ventasService)
    {
        // El service se encarga de aplicar filtros y paginar
        $ventas = $ventasService->list($request);

        return view('admin.ventas.index', [
            'ventas' => $ventas,
        ]);
    }

    /**
     * Detalle de una venta específica
     */
    public function show(int $id, VentasService $ventasService)
    {
        $venta = $ventasService->find($id);

        return view('admin.ventas.show', [
            'venta' => $venta,
        ]);
    }
}
