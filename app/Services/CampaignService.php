<?php

namespace App\Services;

use App\Models\AffiliateCampaign;

class CampaignService
{
    /**
     * Buscar campaña activa
     */
    public function findActiveCampaign(string $slug): ?AffiliateCampaign
    {
        return AffiliateCampaign::where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Validar si campaña está activa según fecha
     */
    public function isCampaignActive(AffiliateCampaign $campaign): bool
    {
        return now()->between($campaign->start_date, $campaign->end_date);
    }
}
