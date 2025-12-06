<?php

namespace App\Services;

use App\Models\MediaKitAsset;

class MediaKitService
{
    public function list()
    {
        return MediaKitAsset::all();
    }

    public function incrementDownload(MediaKitAsset $asset)
    {
        $asset->increment('downloads');
    }

    public function incrementImpression(MediaKitAsset $asset)
    {
        $asset->increment('impressions');
    }
}
