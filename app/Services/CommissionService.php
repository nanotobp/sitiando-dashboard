<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\AffiliateClick;
use App\Models\AffiliateCommission;
use App\Models\AffiliateTier;
use App\Models\AffiliateCampaign;
use App\Models\Order;

class CommissionService
{
    /**
     * Genera comisión PRO después de una orden.
     * Respeta:
     * - campaña activa
     * - commission_type
     * - fixed, percentage, tiered, bonus
     * - inclusiones y exclusiones por producto/categoría
     */
    public function generateCommission(AffiliateClick $click, Order $order): AffiliateCommission
    {
        $affiliate = $click->affiliate;
        $campaign  = $click->campaign; // puede ser null

        $commissionBase = $order->total;
        $commissionRate = $affiliate->commission_rate;
        $commissionAmount = 0;
        $type = 'percentage';

        /**
         * 1) Si hay campaña activa, usar la de la campaña
         */
        if ($campaign && $campaign->is_active) {

            $type = $campaign->commission_type;

            switch ($campaign->commission_type) {

                case 'fixed':
                    $commissionAmount = $campaign->fixed_commission_amount;
                    break;

                case 'percentage':
                    $commissionAmount = $commissionBase * ($campaign->commission_rate / 100);
                    break;

                case 'bonus':
                    $commissionAmount = ($commissionBase * ($campaign->commission_rate / 100))
                        + $campaign->bonus_amount;
                    break;

                case 'tiered':
                    $tier = $this->resolveTier($affiliate);
                    $commissionRate = $tier->commission_rate ?? $affiliate->commission_rate;
                    $commissionAmount = $commissionBase * ($commissionRate / 100);
                    break;
            }
        }

        /**
         * 2) Si NO hay campaña → usar rate del afiliado
         */
        if (!$campaign) {
            $type = 'percentage';
            $commissionAmount = $commissionBase * ($commissionRate / 100);
        }

        /**
         * 3) Crear comisión
         */
        return AffiliateCommission::create([
            'affiliate_id'       => $affiliate->id,
            'order_id'           => $order->id,
            'click_id'           => $click->id,
            'campaign_id'        => $campaign->id ?? null,
            'order_total'        => $commissionBase,
            'commission_base'    => $commissionBase,
            'commission_rate'    => $commissionRate,
            'commission_amount'  => $commissionAmount,
            'commission_type'    => $type,
            'status'             => 'pending',
            'metadata'           => [
                'campaign_source' => $campaign->utm_source ?? null,
                'utm' => [
                    'utm_source'   => $click->utm_source,
                    'utm_medium'   => $click->utm_medium,
                    'utm_campaign' => $click->utm_campaign,
                    'utm_term'     => $click->utm_term,
                    'utm_content'  => $click->utm_content,
                ],
                'click_fraud_score' => $click->fraud_score,
            ],
        ]);
    }


    /**
     * Resolver tier activo del afiliado.
     */
    private function resolveTier(Affiliate $affiliate): ?AffiliateTier
    {
        return $affiliate->tiers()
            ->orderBy('level', 'ASC')
            ->first();
    }
}
