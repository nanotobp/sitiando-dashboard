<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AffiliateAnalyticsService;

class AffiliateAnalyticsController extends Controller
{
    protected AffiliateAnalyticsService $analytics;

    public function __construct(AffiliateAnalyticsService $analytics)
    {
        $this->analytics = $analytics;
    }

    public function index()
    {
        return view('admin.analytics.affiliates', [
            'global'        => $this->analytics->getGlobalStats(),
            'dailySales'    => $this->analytics->salesDaily(),
            'dailyComms'    => $this->analytics->commissionsDaily(),
            'topClicks'     => $this->analytics->topAffiliatesByClicks(),
            'topConvRate'   => $this->analytics->topAffiliatesByConversionRate(),
            'topCommission' => $this->analytics->topAffiliatesByCommission(),
            'noConversions' => $this->analytics->affiliatesWithoutConversions(),
            'financials'    => $this->analytics->financialOverview(),
        ]);
    }
}
