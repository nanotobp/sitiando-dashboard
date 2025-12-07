<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\AffiliateClick;
use App\Models\AffiliateCommission;
use App\Models\AffiliatePayout;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AffiliateAnalyticsService
{
    /**
     * Dashboard general (ADMIN)
     */
    public function getGlobalStats(): array
    {
        return [
            'total_affiliates'    => Affiliate::count(),
            'active_affiliates'   => Affiliate::where('is_active', true)->count(),
            'total_clicks'        => AffiliateClick::count(),
            'total_conversions'   => AffiliateClick::whereNotNull('converted_at')->count(),
            'total_sales'         => Order::whereHas('commissions')->sum('total'),
            'total_commissions'   => AffiliateCommission::sum('commission_amount'),
            'pending_payouts'     => AffiliatePayout::where('status', 'pending')->sum('total_amount'),
            'paid_payouts'        => AffiliatePayout::where('status', 'paid')->sum('total_amount'),
            'conversion_rate'     => $this->globalConversionRate(),
        ];
    }

    /**
     * Tasa de conversión global
     */
    private function globalConversionRate(): float
    {
        $clicks = AffiliateClick::count();
        $conversions = AffiliateClick::whereNotNull('converted_at')->count();

        return $clicks > 0
            ? round(($conversions / $clicks) * 100, 2)
            : 0;
    }

    /**
     * Top afiliados por comisiones
     */
    public function topAffiliatesByCommission(int $limit = 10)
    {
        return Affiliate::select(
            'id',
            'full_name',
            'total_sales',
            'total_commission_earned'
        )
        ->orderBy('total_commission_earned', 'DESC')
        ->limit($limit)
        ->get();
    }

    /**
     * Afiliados sin conversiones
     */
    public function affiliatesWithoutConversions()
    {
        return Affiliate::where('total_conversions', 0)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Comisiones por día (últimos 30 días)
     */
    public function commissionsDaily()
    {
        $since = Carbon::now()->subDays(30);

        return AffiliateCommission::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(commission_amount) as total')
        )
        ->where('created_at', '>=', $since)
        ->groupBy('date')
        ->orderBy('date')
        ->get();
    }

    /**
     * Ventas generadas por afiliados (últimos 30 días)
     */
    public function salesDaily()
    {
        $since = Carbon::now()->subDays(30);

        return Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total) as total')
        )
        ->whereHas('commissions')
        ->where('created_at', '>=', $since)
        ->groupBy('date')
        ->orderBy('date')
        ->get();
    }

    /**
     * Afiliados más activos (por clicks)
     */
    public function topAffiliatesByClicks(int $limit = 10)
    {
        return Affiliate::orderBy('total_clicks', 'DESC')
            ->limit($limit)
            ->get();
    }

    /**
     * Conversión más alta
     */
    public function topAffiliatesByConversionRate(int $limit = 10)
    {
        return Affiliate::where('total_clicks', '>', 0)
            ->orderBy(DB::raw('(total_conversions / total_clicks)'), 'DESC')
            ->limit($limit)
            ->get();
    }

    /**
     * Resumen financiero global
     */
    public function financialOverview(): array
    {
        return [
            'commissions_pending'   => AffiliateCommission::where('status','pending')->sum('commission_amount'),
            'commissions_approved'  => AffiliateCommission::where('status','approved')->sum('commission_amount'),
            'payouts_pending'       => AffiliatePayout::where('status','pending')->sum('total_amount'),
            'payouts_processing'    => AffiliatePayout::where('status','processing')->sum('total_amount'),
            'payouts_paid'          => AffiliatePayout::where('status','paid')->sum('total_amount'),
        ];
    }

    /* =========================================================
       ANALÍTICA INDIVIDUAL DEL AFILIADO
       ========================================================= */

    public function statsFor(Affiliate $a): array
    {
        return [
            'clicks'         => $a->total_clicks,
            'conversions'    => $a->total_conversions,
            'sales'          => $a->total_sales,
            'commission'     => $a->total_commission_earned,
            'pending'        => $a->pending_commission,
            'conversion_rate'=> $a->conversion_rate,
            'aov'            => $a->average_order_value,
        ];
    }

    public function chartsFor(Affiliate $a): array
    {
        $since = now()->subDays(30);

        return [
            'clicks' => AffiliateClick::selectRaw("DATE(created_at) as date, COUNT(*) as total")
                ->where('affiliate_id', $a->id)
                ->where('created_at', '>=', $since)
                ->groupBy('date')->orderBy('date')->get(),

            'conversions' => AffiliateClick::selectRaw("DATE(converted_at) as date, COUNT(*) as total")
                ->where('affiliate_id', $a->id)
                ->whereNotNull('converted_at')
                ->where('converted_at', '>=', $since)
                ->groupBy('date')->orderBy('date')->get(),

            'sales' => Order::selectRaw("DATE(created_at) as date, SUM(total) as total")
                ->whereHas('commissions', fn($q)=>$q->where('affiliate_id',$a->id))
                ->where('created_at','>=',$since)
                ->groupBy('date')->orderBy('date')->get(),

            'commissions' => AffiliateCommission::selectRaw("DATE(created_at) as date, SUM(commission_amount) as total")
                ->where('affiliate_id',$a->id)
                ->where('created_at','>=',$since)
                ->groupBy('date')->orderBy('date')->get(),
        ];
    }

    public function recentOrdersFor(Affiliate $a)
    {
        return Order::whereHas('commissions', fn($q)=>$q->where('affiliate_id',$a->id))
            ->orderBy('created_at','DESC')
            ->limit(5)
            ->get();
    }

    public function recentCommissionsFor(Affiliate $a)
    {
        return AffiliateCommission::where('affiliate_id',$a->id)
            ->orderBy('created_at','DESC')
            ->limit(5)
            ->get();
    }

    public function funnelFor(Affiliate $a): array
    {
        return [
            'clicks'      => $a->total_clicks,
            'conversions' => $a->total_conversions,
            'sales'       => $a->total_sales_count,
            'commission'  => $a->total_commission_earned,
        ];
    }
}
