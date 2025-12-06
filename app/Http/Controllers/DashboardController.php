<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Affiliate;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index', [
            'totalUsuarios' => User::count(),
            'totalProductos' => Product::count(),
            'totalPedidos' => Order::count(),
            'totalAfiliados' => Affiliate::count(),
        ]);
    }
}
