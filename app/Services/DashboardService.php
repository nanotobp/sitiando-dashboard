<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\Affiliate;
use App\Models\AffiliateCommission;
use App\Models\AffiliatePayout;
use App\Models\AffiliateClick;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Dashboard ADMIN PRO — listo para UI avanzada.
     */
    public function getAdminDashboard(): array
    {
        return [

            // ============================================
            // KPI PRINCIPALES (VERSIÓN PRO)
            // ============================================
            'kpis' => [
                'total_users'            => User::count(),
                'total_affiliates'       => Affiliate::count(),
                'total_orders'           => Order::count(),
                'total_sales'            => Order::sum('total'),

                // SOLO comisiones pagadas
                'commissions_paid'       => AffiliateCommission::where('status', 'paid')
                                                ->sum('commission_amount'),

                // Comisiones pendientes (por aprobar o por pagar)
                'commissions_pending'    => AffiliateCommission::where('status', 'pending')
                                                ->sum('commission_amount'),

                // Pagos efectuados a afiliados
                'payouts_paid'           => AffiliatePayout::where('status', 'paid')
                                                ->sum('net_amount'),
            ],

            // ============================================
            // ÓRDENES RECIENTES (VERSIÓN PRO)
            // ============================================
            'recent_orders' => Order::with([
                    'customer',
                    'latestPayment',
                    'lastStatus',
                    'commissions'
                ])
                ->latest()
                ->take(10)
                ->get(),

            // ============================================
            // TOP AFILIADOS PRO
            // ============================================
            'top_affiliates' => Affiliate::select(
                    'id',
                    'full_name',
                    'total_sales',
                    'total_commission_earned',
                    'total_clicks',
                    'total_conversions',
                    'conversion_rate'
                )
                ->orderByDesc('total_commission_earned')
                ->limit(5)
                ->get(),

            // ============================================
            // GRÁFICOS PRO (ventas, comisiones, usuarios)
            // ============================================
            'charts' => [
                'sales_last_30_days'        => $this->salesLast30Days(),
                'orders_last_30_days'       => $this->ordersLast30Days(),
                'commissions_last_30_days'  => $this->commissionsLast30Days(),
                'new_affiliates_last_30'    => $this->newAffiliatesLast30(),
                'clicks_last_30'            => $this->clicksLast30Days(),
                'conversions_last_30'       => $this->conversionsLast30Days(),
            ],
        ];
    }

    // ======================================================
    // MÉTRICAS POR DÍA (PRO)
    // ======================================================

    private function salesLast30Days(): array
    {
        return Order::selectRaw('DATE(created_at) as date, SUM(total) as value')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();
    }

    private function ordersLast30Days(): array
    {
        return Order::selectRaw('DATE(created_at) as date, COUNT(*) as value')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();
    }

    private function commissionsLast30Days(): array
    {
        return AffiliateCommission::selectRaw('DATE(created_at) as date, SUM(commission_amount) as value')
            ->where('status', 'approved')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();
    }

    private function newAffiliatesLast30(): array
    {
        return Affiliate::selectRaw('DATE(joined_at) as date, COUNT(*) as value')
            ->where('joined_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();
    }

    private function clicksLast30Days(): array
    {
        return AffiliateClick::selectRaw('DATE(created_at) as date, COUNT(*) as value')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();
    }

    private function conversionsLast30Days(): array
    {
        return AffiliateClick::selectRaw('DATE(converted_at) as date, COUNT(*) as value')
            ->whereNotNull('converted_at')
            ->where('converted_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();
    }
}
