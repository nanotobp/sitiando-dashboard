<?php

namespace App\Services;

use App\Models\AffiliateCampaign;
use Illuminate\Support\Carbon;

class CampaignService
{
    /**
     * Buscar campaña activa por slug
     */
    public function findActiveCampaign(string $slug): ?AffiliateCampaign
    {
        $campaign = AffiliateCampaign::where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (!$campaign) {
            return null;
        }

        return $this->isCampaignActive($campaign) ? $campaign : null;
    }

    /**
     * Validar si campaña está realmente activa según fechas
     */
    public function isCampaignActive(AffiliateCampaign $campaign): bool
    {
        // Si no tiene fechas → se considera siempre activa
        if (!$campaign->start_date || !$campaign->end_date) {
            return true;
        }

        $now = Carbon::now();

        return $now->between(
            $campaign->start_date,
            $campaign->end_date
        );
    }
}
