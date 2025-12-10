<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\AffiliateClick;
use Illuminate\Support\Str;

class AffiliateService
{
    /**
     * Crear afiliado nuevo con códigos únicos
     */
    public function create(array $data): Affiliate
    {
        $data['affiliate_code'] = $this->generateAffiliateCode();
        $data['referral_code']  = $this->generateReferralCode();
        $data['joined_at']      = now();

        return Affiliate::create($data);
    }

    /**
     * Código único para affiliate_code (UUID)
     */
    private function generateAffiliateCode(): string
    {
        return (string) Str::uuid();
    }

    /**
     * Código único para referral_code (no UUID, sino código comercial)
     */
    private function generateReferralCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (Affiliate::where('referral_code', $code)->exists());

        return $code;
    }

    /**
     * Buscar por referral_code
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
     * Registrar click REAL (NO incrementa columnas inexistentes)
     */
    public function registerClick(Affiliate $affiliate, array $data = []): AffiliateClick
    {
        return AffiliateClick::create([
            'affiliate_id'      => $affiliate->id,
            'ip_address'        => $data['ip'] ?? request()->ip(),
            'user_agent'        => $data['agent'] ?? request()->userAgent(),
            'referral_code'     => $affiliate->referral_code,
            'landing_page'      => $data['landing'] ?? request()->fullUrl(),
            'device_fingerprint'=> $data['fingerprint'] ?? null,
        ]);
    }

    /**
     * Registrar conversión REAL para un click
     */
    public function registerConversion(AffiliateClick $click, string $orderId): void
    {
        $click->update([
            'converted'    => true,
            'converted_at' => now(),
            'order_id'     => $orderId,
        ]);
    }
}
