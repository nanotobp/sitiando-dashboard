<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Services\AffiliateAnalyticsService;

class AffiliateAnalyticsDetailController extends Controller
{
    protected AffiliateAnalyticsService $analytics;

    public function __construct(AffiliateAnalyticsService $analytics)
    {
        $this->analytics = $analytics;
    }

    public function show($id)
    {
        $affiliate = Affiliate::findOrFail($id);

        return view('admin.analytics.affiliate-detail', [
            'affiliate'   => $affiliate,
            'stats'       => $this->analytics->statsFor($affiliate),
            'charts'      => $this->analytics->chartsFor($affiliate),
            'recentOrders'     => $this->analytics->recentOrdersFor($affiliate),
            'recentCommissions' => $this->analytics->recentCommissionsFor($affiliate),
            'funnel'      => $this->analytics->funnelFor($affiliate),
        ]);
    }
}
