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
     * Retorna todos los datos del dashboard admin.
     */
    public function getAdminDashboard(): array
    {
        return [
            'kpis'           => $this->getKpis(),
            'recent_orders'  => $this->recentOrders(),
            'top_affiliates' => $this->topAffiliates(),
            'charts'         => $this->chartData(),
        ];
    }


    /* ============================================================
       1) KPIs PRINCIPALES
    ============================================================ */

    public function getKpis(): array
    {
        $monthStart = Carbon::now()->startOfMonth();
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Ingresos del mes actual
        $revenueThisMonth = Order::where('status', 'paid')
            ->where('created_at', '>=', $monthStart)
            ->sum('total');

        // Ingresos del mes pasado
        $revenueLastMonth = Order::where('status', 'paid')
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->sum('total');

        // Porcentaje
        $revenueChange = $this->percentChange($revenueLastMonth, $revenueThisMonth);

        // Órdenes
        $ordersThisMonth = Order::where('created_at', '>=', $monthStart)->count();
        $ordersLastMonth = Order::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
        $ordersChange    = $this->percentChange($ordersLastMonth, $ordersThisMonth);

        // Ticket promedio
        $averageTicket = Order::where('status', 'paid')->avg('total') ?? 0;

        // Usuarios nuevos
        $newUsers = User::where('created_at', '>=', $monthStart)->count();
        $oldUsers = User::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
        $userChange = $this->percentChange($oldUsers, $newUsers);

        // Afiliados activos
        $activeAffiliates = Affiliate::where('is_active', true)->count();

        return [
            'monthly_revenue'        => $revenueThisMonth,
            'monthly_revenue_change' => $revenueChange,
            'monthly_orders'         => $ordersThisMonth,
            'monthly_orders_change'  => $ordersChange,
            'average_ticket'         => $averageTicket,
            'new_users'              => $newUsers,
            'new_users_change'       => $userChange,
            'active_affiliates'      => $activeAffiliates,
        ];
    }


    /* ============================================================
       2) ÓRDENES RECIENTES
    ============================================================ */

    public function recentOrders()
    {
        return Order::latest()
            ->limit(7)
            ->get();
    }


    /* ============================================================
       3) TOP AFILIADOS
    ============================================================ */

    public function topAffiliates()
    {
        return Affiliate::orderBy('total_commission_earned', 'DESC')
            ->limit(7)
            ->get();
    }


    /* ============================================================
       4) CHARTS (REVENUE + ORDERS + SPARKLINES)
    ============================================================ */

    public function chartData(): array
    {
        $start = Carbon::now()->subDays(30);

        // Revenue diarios (gráfico principal)
        $revenue = Order::selectRaw('DATE(created_at) as date, SUM(total) as value')
            ->where('created_at', '>=', $start)
            ->where('status', 'paid')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Órdenes por día
        $orders = Order::selectRaw('DATE(created_at) as date, COUNT(*) as value')
            ->where('created_at', '>=', $start)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Sparklines
        $spark_revenue = $revenue->pluck('value');
        $spark_orders  = $orders->pluck('value');

        return [
            'revenue'       => $revenue,
            'orders'        => $orders,
            'sparkRevenue'  => $spark_revenue,
            'sparkOrders'   => $spark_orders,
            'sparkTicket'   => $this->fakeSparkline(),
            'sparkUsers'    => $this->fakeSparkline(),
            'sparkAffiliates' => $this->fakeSparkline(),
        ];
    }


    /* ============================================================
       5) Utility: cambio porcentual
    ============================================================ */

    private function percentChange($old, $new): float
    {
        if ($old == 0) {
            return $new > 0 ? 100 : 0;
        }

        return round((($new - $old) / $old) * 100, 2);
    }


    /* ============================================================
       6) Utility: generar sparkline dummy
    ============================================================ */
    private function fakeSparkline(): array
    {
        return collect(range(1, 20))->map(fn() => rand(1, 100))->toArray();
    }
}
