<?php

namespace App\Services;

use App\Models\AffiliateClick;
use App\Models\Affiliate;
use App\Models\Order;
use Illuminate\Support\Str;

class TrackingService
{
    /**
     * Registrar clic de afiliado
     */
    public function logClick(array $data): AffiliateClick
    {
        return AffiliateClick::create([
            'id' => Str::uuid()->toString(),
            'affiliate_id' => $data['affiliate_id'],
            'referral_code' => $data['referral_code'],
            'product_id' => $data['product_id'] ?? null,
            'campaign_id' => $data['campaign_id'] ?? null,
            'ip_address' => $data['ip'],
            'user_agent' => $data['ua'],
            'referrer_url' => $data['referrer'] ?? null,
            'landing_page' => $data['landing'] ?? null,
            'session_id' => $data['session_id'] ?? null,
            'cookie_value' => $data['cookie'] ?? null,
            'expires_at' => now()->addHours(24),
            'utm_source' => $data['utm_source'] ?? null,
            'utm_medium' => $data['utm_medium'] ?? null,
            'utm_campaign' => $data['utm_campaign'] ?? null,
            'utm_term' => $data['utm_term'] ?? null,
            'utm_content' => $data['utm_content'] ?? null,
            'country_code' => $data['country'] ?? null,
            'city' => $data['city'] ?? null,
        ]);
    }

    /**
     * Vincular click con orden (conversión)
     */
    public function attachClickToOrder(AffiliateClick $click, Order $order)
    {
        $click->update([
            'converted' => true,
            'order_id' => $order->id,
            'converted_at' => now(),
        ]);
    }
}
