<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\AffiliateClick;
use App\Models\AffiliateCommission;
use App\Models\Order;

class CommissionService
{
    /**
     * Genera comisión después de una orden
     */
    public function generateCommission(AffiliateClick $click, Order $order): AffiliateCommission
    {
        $affiliate = $click->affiliate;

        $commissionBase = $order->total;
        $rate = $affiliate->commission_rate;
        $amount = $commissionBase * ($rate / 100);

        return AffiliateCommission::create([
            'affiliate_id' => $affiliate->id,
            'order_id' => $order->id,
            'click_id' => $click->id,
            'commission_base' => $commissionBase,
            'commission_rate' => $rate,
            'commission_amount' => $amount,
            'commission_type' => 'percentage',
            'status' => 'pending',
        ]);
    }
}
