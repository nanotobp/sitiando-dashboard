<?php

namespace App\Services;

use App\Models\Affiliate;
use Illuminate\Support\Str;

class AffiliateService
{
    /**
     * Crear un afiliado nuevo
     */
    public function create(array $data): Affiliate
    {
        $data['affiliate_code'] = Str::uuid()->toString();
        $data['referral_code'] = strtoupper(Str::random(8));
        $data['joined_at'] = now();

        return Affiliate::create($data);
    }

    /**
     * Buscar afiliado por referral_code
     */
    public function findByReferralCode(string $code): ?Affiliate
    {
        return Affiliate::where('referral_code', $code)->first();
    }

    /**
     * Buscar por affiliate_code
     */
    public function findByAffiliateCode(string $code): ?Affiliate
    {
        return Affiliate::where('affiliate_code', $code)->first();
    }

    /**
     * Actualizar estadísticas
     */
    public function incrementClicks(Affiliate $affiliate): void
    {
        $affiliate->increment('total_clicks');
    }

    public function incrementConversions(Affiliate $affiliate): void
    {
        $affiliate->increment('total_conversions');
    }
}
