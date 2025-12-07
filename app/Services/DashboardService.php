<?php

namespace App\Services;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Carbon\Carbon;

class DashboardService
{
    public function totalUsuarios()
    {
        return User::count();
    }

    public function totalProductos()
    {
        return Product::count();
    }

    public function totalOrders()
    {
        return Order::count();
    }

    public function totalAffiliates()
    {
        return User::where('role', 'affiliate')->count();
    }

    public function ventasMes()
    {
        return Order::whereMonth('created_at', now()->month)->sum('total');
    }

    public function ultimosProductos()
    {
        return Product::latest()->limit(5)->get();
    }

    public function ultimasOrdenes()
    {
        return Order::latest()->limit(5)->get();
    }

    /* ---------------------------------------
       DATASETS PARA GRÁFICOS GRANDES
    --------------------------------------- */

    public function revenueData()
    {
        return Order::selectRaw('MONTH(created_at) as m, SUM(total) as total')
            ->groupBy('m')
            ->orderBy('m')
            ->pluck('total')
            ->toArray();
    }

    public function retentionData()
    {
        // ejemplo simple: usuarios que repiten compras
        return [
            70, 75, 68, 80, 82, 78
        ];
    }

    /* ---------------------------------------
       SPARKLINES PARA LOS KPIs
    --------------------------------------- */
    public function sparkData()
    {
        return [
            'sparkUsuarios'   => [5, 8, 6, 9, 12, 15, 14],
            'sparkProductos'  => [2, 3, 4, 8, 6, 7, 9],
            'sparkOrders'     => [10, 9, 7, 8, 7, 6, 7],
            'sparkAffiliates' => [1, 3, 4, 6, 8, 12, 15],
            'sparkVentasMes'  => [200, 300, 250, 400, 350, 500, 600]
        ];
    }
}
