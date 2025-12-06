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
    public function getDashboard(Affiliate $affiliate): array
    {
        return [
            'stats' => $this->getStats($affiliate),
            'charts' => $this->getCharts($affiliate),
            'recent_orders' => $this->getRecentOrders($affiliate),
            'recent_payouts' => $this->getRecentPayouts($affiliate),
            'active_campaigns' => $this->getActiveCampaigns($affiliate),
        ];
    }

    private function getStats(Affiliate $affiliate): array
    {
        return [
            'total_clicks' => $affiliate->total_clicks,
            'total_conversions' => $affiliate->total_conversions,
            'total_sales' => $affiliate->total_sales,
            'total_commission_earned' => $affiliate->total_commission_earned,
            'pending_commission' => $affiliate->pending_commission,
            'conversion_rate' => $affiliate->conversion_rate,
            'average_order_value' => $affiliate->average_order_value,
        ];
    }

    private function getCharts(Affiliate $affiliate): array
    {
        $since = Carbon::now()->subDays(30);

        $clicks = AffiliateClick::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->where('affiliate_id', $affiliate->id)
            ->where('created_at', '>=', $since)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $conversions = AffiliateClick::select(
                DB::raw('DATE(converted_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->where('affiliate_id', $affiliate->id)
            ->whereNotNull('converted_at')
            ->where('converted_at', '>=', $since)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $commissions = AffiliateCommission::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(commission_amount) as total')
            )
            ->where('affiliate_id', $affiliate->id)
            ->where('created_at', '>=', $since)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $sales = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total')
            )
            ->whereHas('commissions', function ($q) use ($affiliate) {
                $q->where('affiliate_id', $affiliate->id);
            })
            ->where('created_at', '>=', $since)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'clicks' => $clicks,
            'conversions' => $conversions,
            'commissions' => $commissions,
            'sales' => $sales
        ];
    }

    private function getRecentOrders(Affiliate $affiliate)
    {
        return Order::whereHas('commissions', function ($q) use ($affiliate) {
                $q->where('affiliate_id', $affiliate->id);
            })
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();
    }

    private function getRecentPayouts(Affiliate $affiliate)
    {
        return AffiliatePayout::where('affiliate_id', $affiliate->id)
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();
    }

    private function getActiveCampaigns(Affiliate $affiliate)
    {
        return $affiliate->campaignMemberships()
            ->with('campaign')
            ->whereHas('campaign', function ($q) {
                $q->where('is_active', true);
            })
            ->get()
            ->pluck('campaign');
    }
}
