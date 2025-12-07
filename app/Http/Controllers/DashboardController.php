<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    /**
     * Inyectamos el servicio vía constructor.
     */
    public function __construct(DashboardService $dashboardService)
    {
        $this->middleware(['auth']); // seguridad
        $this->dashboardService = $dashboardService;
    }

    /**
     * Muestra el dashboard del admin (Ultra PRO).
     */
    public function index()
    {
        $data = $this->dashboardService->getAdminDashboard();

        return view('admin.dashboard.index', $data);
    }
}
