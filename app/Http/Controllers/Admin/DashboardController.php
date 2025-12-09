<?php

namespace App\Http\Controllers.Admin;

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
        // seguridad del backoffice
        $this->middleware(['auth', 'roles:admin']);

        $this->dashboardService = $dashboardService;
    }

    /**
     * Dashboard administrativo PRO.
     */
    public function index()
    {
        $data = $this->dashboardService->getAdminDashboard();

        return view('admin.dashboard.index', $data);
    }
}
