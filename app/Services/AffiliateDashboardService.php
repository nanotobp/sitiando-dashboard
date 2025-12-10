<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\AffiliateClick;
use App\Models\AffiliateCommission;
use App\Models\AffiliatePayout;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AffiliateDashboardService
{
    /**
     * Dashboard del afiliado (vista pública para cada uno)
     */
    public function getDashboard(Affiliate $affiliate): array
    {
        return [
            'stats'          => $this->getStats($affiliate),
            'charts'         => $this->getCharts($affiliate),
            'recent_orders'  => $this->getRecentOrders($affiliate),
            'recent_payouts' => $this->getRecentPayouts($affiliate),
            'active_campaigns' => $this->getActiveCampaigns($affiliate),
        ];
    }


    /**
     * Métricas instantáneas del afiliado
     */
    private function getStats(Affiliate $affiliate): array
    {
        return [
            'total_clicks'          => $affiliate->total_clicks,
            'total_conversions'     => $affiliate->total_conversions,
            'total_sales'           => $affiliate->total_sales,
            'total_commission_earned' => $affiliate->total_commission_earned,
            'pending_commission'    => $affiliate->pending_commission,
            'conversion_rate'       => $affiliate->conversion_rate,
            'average_order_value'   => $affiliate->average_order_value,
        ];
    }


    /**
     * Datos para gráficos de líneas (últimos 30 días)
     */
    private function getCharts(Affiliate $affiliate): array
    {
        $since = Carbon::now()->subDays(30);

        // CLICK DIARIO
        $clicks = AffiliateClick::selectRaw("CAST(created_at AS DATE) AS date, COUNT(*) AS total")
            ->where('affiliate_id', $affiliate->id)
            ->where('created_at', '>=', $since)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // CONVERSIONES DIARIAS
        $conversions = AffiliateClick::selectRaw("CAST(converted_at AS DATE) AS date, COUNT(*) AS total")
            ->where('affiliate_id', $affiliate->id)
            ->whereNotNull('converted_at')
            ->where('converted_at', '>=', $since)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // COMISIONES DIARIAS
        $commissions = AffiliateCommission::selectRaw("CAST(created_at AS DATE) AS date, SUM(commission_amount) AS total")
            ->where('affiliate_id', $affiliate->id)
            ->where('created_at', '>=', $since)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // VENTAS DIARIAS
        $sales = Order::selectRaw("CAST(created_at AS DATE) AS date, SUM(total) AS total")
            ->whereHas('commissions', function ($q) use ($affiliate) {
                $q->where('affiliate_id', $affiliate->id);
            })
            ->where('created_at', '>=', $since)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'clicks'       => $clicks,
            'conversions'  => $conversions,
            'commissions'  => $commissions,
            'sales'        => $sales,
        ];
    }


    /**
     * Últimas órdenes que generaron comisión
     */
    private function getRecentOrders(Affiliate $affiliate)
    {
        return Order::whereHas('commissions', fn ($q) =>
                $q->where('affiliate_id', $affiliate->id)
            )
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();
    }


    /**
     * Últimos pagos al afiliado
     */
    private function getRecentPayouts(Affiliate $affiliate)
    {
        return AffiliatePayout::where('affiliate_id', $affiliate->id)
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();
    }


    /**
     * Campañas activas a las que pertenece el afiliado
     */
    private function getActiveCampaigns(Affiliate $affiliate)
    {
        return $affiliate->campaignMemberships()
            ->whereHas('campaign', fn ($q) => $q->where('is_active', true))
            ->with('campaign')
            ->get()
            ->pluck('campaign');
    }
}
