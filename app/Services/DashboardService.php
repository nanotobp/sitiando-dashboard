<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\Affiliate;
use App\Models\AffiliateCommission;
use Carbon\Carbon;

class DashboardService
{
    /**
     * Retorna la data PRO para el dashboard del admin.
     */
    public function getAdminDashboard(): array
    {
        return [

            // ======================
            // KPIs PRINCIPALES
            // ======================
            'kpis' => [
                'total_users'      => User::count(),
                'total_affiliates' => Affiliate::count(),
                'total_orders'     => Order::count(),
                'total_sales'      => Order::sum('total'),
                'commissions_paid' => AffiliateCommission::sum('commission_amount'),
            ],

            // ======================
            // ÓRDENES RECIENTES
            // ======================
            'recent_orders' => Order::latest()
                ->take(10)
                ->get(),

            // ======================
            // AFILIADOS TOP
            // ======================
            'top_affiliates' => Affiliate::withSum('commissions', 'commission_amount')
                ->orderByDesc('commissions_sum_commission_amount')
                ->take(5)
                ->get(),

            // ======================
            // CHARTS (dummy seguros)
            // ======================
            'charts' => [
                'sales_last_30_days' => $this->salesLast30Days(),
                'orders_by_day'      => $this->ordersLast30Days(),
            ],
        ];
    }


    private function salesLast30Days(): array
    {
        return Order::selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();
    }


    private function ordersLast30Days(): array
    {
        return Order::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();
    }
}
