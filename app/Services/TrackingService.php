<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\AffiliateClick;
use App\Models\Order;
use App\Models\UtmTracking;
use Illuminate\Support\Str;

class TrackingService
{
    /**
     * Registrar clic de afiliado (PRO)
     */
    public function logClick(array $data): AffiliateClick
    {
        // Crear click
        $click = AffiliateClick::create([
            // UUID automático vía HasUuids
            'affiliate_id'     => $data['affiliate_id'],
            'referral_code'    => $data['referral_code'] ?? null,
            'product_id'       => $data['product_id'] ?? null,
            'campaign_id'      => $data['campaign_id'] ?? null,

            'ip_address'       => $data['ip'] ?? null,
            'user_agent'       => $data['ua'] ?? null,
            'referrer_url'     => $data['referrer'] ?? null,
            'landing_page'     => $data['landing'] ?? null,

            'session_id'       => $data['session_id'] ?? null,
            'cookie_value'     => $data['cookie'] ?? null,
            'device_fingerprint' => $data['fingerprint'] ?? null,

            'expires_at'       => now()->addHours(24),

            // UTM full
            'utm_source'       => $data['utm_source'] ?? null,
            'utm_medium'       => $data['utm_medium'] ?? null,
            'utm_campaign'     => $data['utm_campaign'] ?? null,
            'utm_term'         => $data['utm_term'] ?? null,
            'utm_content'      => $data['utm_content'] ?? null,

            'country_code'     => $data['country'] ?? null,
            'city'             => $data['city'] ?? null,
        ]);

        // Actualizar estadísticas del afiliado
        Affiliate::where('id', $click->affiliate_id)
            ->increment('total_clicks');

        return $click;
    }

    /**
     * Registrar conversión y asociar clic con orden
     * + almacenar UTMs reales (utm_tracking)
     */
    public function attachClickToOrder(AffiliateClick $click, Order $order)
    {
        // Marcar conversión en el click
        $click->update([
            'converted'    => true,
            'order_id'     => $order->id,
            'converted_at' => now(),
        ]);

        // Actualizar métricas del afiliado
        Affiliate::where('id', $click->affiliate_id)
            ->increment('total_conversions');

        // Registrar UTMs si existen
        UtmTracking::create([
            'click_id'     => $click->id,
            'order_id'     => $order->id,
            'utm_source'   => $click->utm_source,
            'utm_medium'   => $click->utm_medium,
            'utm_campaign' => $click->utm_campaign,
            'utm_term'     => $click->utm_term,
            'utm_content'  => $click->utm_content,
        ]);
    }
}
