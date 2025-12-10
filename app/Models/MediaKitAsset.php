<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MediaKitAsset extends Model
{
    use HasUuids;

    protected $table = 'media_kit_assets';

    public $incrementing = false;
    protected $keyType = 'string';

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
        'metadata',
    ];

    protected $casts = [
        'tags'       => 'array',   // soporta jsonb o text[]
        'metadata'   => 'array',
        'downloads'  => 'integer',
        'impressions'=> 'integer',
    ];

    /* ==========================================================
       RELACIONES
    ========================================================== */

    public function campaign()
    {
        return $this->belongsTo(AffiliateCampaign::class, 'campaign_id');
    }

    /* ==========================================================
       MÉTODOS ÚTILES
    ========================================================== */

    /** Registrar descarga */
    public function registerDownload()
    {
        $this->increment('downloads');
        return $this;
    }

    /** Registrar impresión (vista del asset) */
    public function registerImpression()
    {
        $this->increment('impressions');
        return $this;
    }
}
