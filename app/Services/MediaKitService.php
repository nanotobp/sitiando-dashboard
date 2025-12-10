<?php

namespace App\Services;

use App\Models\MediaKitAsset;
use App\Models\Affiliate;
use Illuminate\Support\Facades\Log;

class MediaKitService
{
    /**
     * Listado de assets visibles para el panel.
     * Si se pasa un afiliado, solo retorna los disponibles para sus campañas.
     */
    public function list(?Affiliate $affiliate = null)
    {
        $query = MediaKitAsset::query()->orderBy('created_at', 'desc');

        if ($affiliate) {
            // En caso de que un asset pertenezca a una campaña específica
            $campaignIds = $affiliate->campaignMemberships()->pluck('campaign_id');

            $query->whereNull('campaign_id')
                  ->orWhereIn('campaign_id', $campaignIds);
        }

        return $query->get();
    }

    /**
     * Registra una descarga del asset.
     */
    public function incrementDownload(MediaKitAsset $asset, ?Affiliate $affiliate = null): void
    {
        $asset->increment('downloads');

        // Log PRO para auditoría
        Log::info("MediaKitAsset downloaded", [
            'asset_id'   => $asset->id,
            'affiliate'  => $affiliate?->id,
            'ip'         => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Registra una impresión del asset.
     */
    public function incrementImpression(MediaKitAsset $asset, ?Affiliate $affiliate = null): void
    {
        $asset->increment('impressions');

        Log::info("MediaKitAsset impression", [
            'asset_id'   => $asset->id,
            'affiliate'  => $affiliate?->id,
            'ip'         => request()->ip(),
            'user_agent' => request()->userAgent(),
            'referer'    => request()->headers->get('referer'),
        ]);
    }
}
