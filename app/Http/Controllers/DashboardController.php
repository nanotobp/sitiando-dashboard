<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Affiliate;

class DashboardController extends Controller
{
    public function index()
    {
        // KPIs principales
        $totalUsuarios = User::count();
        $totalProductos = Product::count();
        $totalOrders = Order::count();
        $totalAffiliates = Affiliate::count();

        // Ventas del mes (si tu tabla orders tiene 'total' y 'created_at')
        $ventasMes = Order::whereMonth('created_at', now()->month)
                          ->sum('total');

        // Últimas órdenes (si existen)
        $ultimasOrdenes = Order::latest()->limit(5)->get();

        // Últimos productos agregados
        $ultimosProductos = Product::latest()->limit(5)->get();

        return view('dashboard.index', compact(
            'totalUsuarios',
            'totalProductos',
            'totalOrders',
            'totalAffiliates',
            'ventasMes',
            'ultimasOrdenes',
            'ultimosProductos'
        ));
    }
}
