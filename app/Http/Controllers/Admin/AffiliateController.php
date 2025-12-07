<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\Order;
use App\Models\AffiliateCommission;
use App\Models\AffiliateClicks;

class AffiliateController extends Controller
{
    /**
     * Listado de afiliados
     */
    public function index()
    {
        $affiliates = Affiliate::orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.affiliates.index', compact('affiliates'));
    }

    /**
     * Ficha completa del afiliado
     */
    public function show($id)
    {
        $affiliate = Affiliate::findOrFail($id);

        // Estadísticas principales
        $stats = [
            'total_clicks'      => $affiliate->total_clicks,
            'total_sales'       => $affiliate->total_sales,
            'total_commission'  => $affiliate->total_commission_earned,
            'paid_commission'   => $affiliate->total_commission_paid,
            'pending_commission'=> $affiliate->pending_commission,
            'conversion_rate'   => $affiliate->conversion_rate,
            'avg_order_value'   => $affiliate->average_order_value,
        ];

        // Ventas reales asociadas
        $orders = Order::where('affiliate_id', $affiliate->id)
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        // Últimos clics
        $clicks = \App\Models\AffiliateClicks::where('affiliate_id', $affiliate->id)
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        return view('admin.affiliates.show', compact('affiliate', 'stats', 'orders', 'clicks'));
    }
}
