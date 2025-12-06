<?php

namespace App\Services;

use App\Models\AffiliateClick;
use App\Models\AffiliateFraudLog;

class FraudService
{
    public function evaluateClick(AffiliateClick $click): void
    {
        $score = 0;
        $reason = [];

        if ($click->ip_address === '127.0.0.1') {
            $score += 80;
            $reason[] = 'Localhost IP';
        }

        if ($score > 50) {
            AffiliateFraudLog::create([
                'affiliate_id' => $click->affiliate_id,
                'click_id' => $click->id,
                'score' => $score,
                'reason' => json_encode($reason),
                'ip_address' => $click->ip_address,
                'user_agent' => $click->user_agent,
            ]);

            $click->update([
                'is_flagged' => true,
                'flag_reason' => implode(', ', $reason),
            ]);
        }
    }
}
