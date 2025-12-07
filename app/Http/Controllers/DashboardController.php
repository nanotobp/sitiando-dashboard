<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Últimos 12 meses
        $from = now()->copy()->subMonths(11)->startOfMonth();

        // Revenue y órdenes por mes (PostgreSQL)
        $byMonth = Order::selectRaw("
                DATE_TRUNC('month', created_at) as month_date,
                TO_CHAR(DATE_TRUNC('month', created_at), 'Mon YY') as month_label,
                SUM(total) as revenue,
                COUNT(*) as orders_count
            ")
            ->where('created_at', '>=', $from)
            ->groupBy('month_date', 'month_label')
            ->orderBy('month_date')
            ->get();

        $labels       = $byMonth->pluck('month_label');
        $revenueData  = $byMonth->pluck('revenue');
        $ordersData   = $byMonth->pluck('orders_count');

        // KPIs base
        $totalRevenue   = Order::sum('total');
        $totalOrders    = Order::count();
        $paidOrders     = Order::where('status', 'paid')->count();
        $pendingOrders  = Order::where('status', 'pending')->count();
        $failedOrders   = Order::where('status', 'failed')->count();

        $totalProducts  = Product::count();
        $totalUsers     = User::count();

        $avgTicket = $totalOrders > 0
            ? round($totalRevenue / $totalOrders, 0)
            : 0;

        // Revenue este mes vs mes pasado (para % cambio)
        $currentMonthRevenue = Order::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('total');

        $previousMonthRevenue = Order::whereBetween('created_at', [
                now()->copy()->subMonth()->startOfMonth(),
                now()->copy()->subMonth()->endOfMonth(),
            ])->sum('total');

        if ($previousMonthRevenue > 0) {
            $revenueGrowth = (($currentMonthRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100;
        } else {
            $revenueGrowth = null;
        }

        // Conversión simple: órdenes pagadas / órdenes totales
        $conversionRate = $totalOrders > 0
            ? round(($paidOrders / max($totalOrders, 1)) * 100, 1)
            : 0;

        // Top productos (usando order_items)
        $topProducts = DB::table('order_items')
            ->select(
                'product_name',
                DB::raw('SUM(quantity) as qty'),
                DB::raw('SUM(quantity * price) as revenue')
            )
            ->groupBy('product_name')
            ->orderByDesc('qty')
            ->limit(5)
            ->get();

        // Últimas órdenes
        $recentOrders = Order::with('items')
            ->latest()
            ->limit(8)
            ->get();

        // Datos para JS (charts)
        $chartPayload = [
            'labels'       => $labels,
            'revenue'      => $revenueData,
            'orders'       => $ordersData,
            'kpis'         => [
                'totalRevenue'      => $totalRevenue,
                'currentMonth'      => $currentMonthRevenue,
                'previousMonth'     => $previousMonthRevenue,
                'revenueGrowth'     => $revenueGrowth,
                'totalOrders'       => $totalOrders,
                'paidOrders'        => $paidOrders,
                'pendingOrders'     => $pendingOrders,
                'failedOrders'      => $failedOrders,
                'avgTicket'         => $avgTicket,
                'conversionRate'    => $conversionRate,
            ],
        ];

        return view('admin.dashboard.index', [
            'totalRevenue'   => $totalRevenue,
            'totalOrders'    => $totalOrders,
            'totalProducts'  => $totalProducts,
            'totalUsers'     => $totalUsers,
            'avgTicket'      => $avgTicket,
            'paidOrders'     => $paidOrders,
            'pendingOrders'  => $pendingOrders,
            'failedOrders'   => $failedOrders,
            'recentOrders'   => $recentOrders,
            'topProducts'    => $topProducts,
            'chartPayload'   => $chartPayload,
        ]);
    }
}
