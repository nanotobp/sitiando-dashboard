<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\AffiliateCommission;
use App\Models\AffiliatePayout;

class PayoutService
{
    /**
     * Generar payout mensual
     */
    public function generatePayout(Affiliate $affiliate)
    {
        $commissions = AffiliateCommission::where('affiliate_id', $affiliate->id)
            ->where('status', 'approved')
            ->whereNull('paid_at')
            ->get();

        $total = $commissions->sum('commission_amount');

        return AffiliatePayout::create([
            'affiliate_id' => $affiliate->id,
            'commission_ids' => $commissions->pluck('id')->toArray(),
            'total_amount' => $total,
            'net_amount' => $total,
            'status' => 'pending',
            'period_start' => now()->startOfMonth(),
            'period_end' => now()->endOfMonth(),
        ]);
    }
}
