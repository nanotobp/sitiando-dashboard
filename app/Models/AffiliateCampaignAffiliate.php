<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AffiliateCampaignAffiliate extends Model
{
    use HasUuids;

    protected $table = 'affiliate_campaign_affiliates';

    protected $fillable = [
        'campaign_id',
        'affiliate_id',
        'joined_at',
        'approved',
    ];

    protected $casts = [
        'approved' => 'boolean',
        'joined_at' => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(AffiliateCampaign::class, 'campaign_id');
    }

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class, 'affiliate_id');
    }
}
