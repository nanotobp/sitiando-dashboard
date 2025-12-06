<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Estos datos serán reemplazados luego por queries reales a la DB
        $stats = [
            'ventas_hoy' => 35000000,     // Gs total hoy
            'ventas_mes' => 820000000,    // Gs mes actual
            'productos_total' => 1540,
            'usuarios_activos' => 128,
            'ordenes_pendientes' => 17,
            'ordenes_en_camino' => 5,
        ];

        // Datos para gráfico (ventas últimos 7 días)
        $chartData = [
            'labels' => [
                'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'
            ],
            'values' => [
                12000000, 15000000, 9000000, 11000000, 18000000, 22000000, 17000000
            ]
        ];

        // Últimas actividades (simulación)
        $activity = [
            ['msg' => 'Nueva orden cargada', 'time' => 'Hace 2 horas'],
            ['msg' => 'Producto sin stock: Coca-Cola 2L', 'time' => 'Hace 4 horas'],
            ['msg' => 'Nuevo usuario registrado', 'time' => 'Ayer'],
        ];

        return view('dashboard.index', compact('stats', 'chartData', 'activity'));
    }
}
