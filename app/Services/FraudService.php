<?php

namespace App\Services;

use App\Models\AffiliateClick;
use App\Models\AffiliateFraudLog;

class FraudService
{
    /**
     * Evalúa un click y lo marca como fraudulento si supera el umbral.
     */
    public function evaluateClick(AffiliateClick $click): void
    {
        $score = 0;
        $reasons = [];

        // ===========================================
        // 1) REGLA: IP localhost (desarrollo)
        // ===========================================
        if ($click->ip_address === '127.0.0.1') {
            $score += 80;
            $reasons[] = 'Localhost IP detected';
        }

        // ===========================================
        // 2) REGLA: User-Agent sospechoso
        // ===========================================
        if ($this->isBotUserAgent($click->user_agent)) {
            $score += 40;
            $reasons[] = 'Suspicious User-Agent (possible bot)';
        }

        // ===========================================
        // 3) REGLA: Falta de UTM en campañas obligatorias
        // ===========================================
        if ($click->utm_source === null) {
            $score += 20;
            $reasons[] = 'Missing UTM source';
        }

        // ===========================================
        // 4) REGLA: Fingerprint vacío o repetido
        // ===========================================
        if ($click->device_fingerprint === null) {
            $score += 25;
            $reasons[] = 'Missing device fingerprint';
        }

        // ===========================================
        // REGISTRO DE FRAUDE
        // ===========================================
        if ($score > 50) {
            AffiliateFraudLog::create([
                'affiliate_id' => $click->affiliate_id,
                'click_id'     => $click->id,
                'score'        => $score,
                'reason'       => $reasons, // ← array, NO json_encode
                'ip_address'   => $click->ip_address,
                'user_agent'   => $click->user_agent,
                'fingerprint'  => $click->device_fingerprint,
            ]);

            $click->update([
                'is_flagged'  => true,
                'flag_reason' => implode(', ', $reasons),
            ]);
        }
    }

    /**
     * Detectar User-Agent sospechosos
     */
    private function isBotUserAgent(?string $ua): bool
    {
        if (!$ua) return true;

        $botFragments = [
            'bot', 'crawl', 'spider', 'curl', 'wget', 'python', 'scrapy',
        ];

        $uaLower = strtolower($ua);

        foreach ($botFragments as $f) {
            if (str_contains($uaLower, $f)) {
                return true;
            }
        }

        return false;
    }
}
