<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MediaKitAsset extends Model
{
    use HasUuids;

    protected $table = 'media_kit_assets';

    protected $fillable = [
        'name',
        'description',
        'file_url',
        'thumbnail_url',
        'file_type',
        'tags',
        'campaign_id',
        'downloads',
        'impressions',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function campaign()
    {
        return $this->belongsTo(AffiliateCampaign::class, 'campaign_id');
    }
}
