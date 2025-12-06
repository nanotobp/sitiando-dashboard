<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AffiliateCampaign extends Model
{
    use HasUuids;

    protected $table = 'affiliate_campaigns';

    protected $fillable = [
        'name',
        'description',
        'slug',
        'start_date',
        'end_date',
        'commission_type',
        'commission_rate',
        'fixed_commission_amount',
        'bonus_amount',
        'included_products',
        'excluded_products',
        'included_categories',
        'excluded_categories',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'is_active',
        'visibility',
        'metadata',
    ];

    protected $casts = [
        'included_products' => 'array',
        'excluded_products' => 'array',
        'included_categories' => 'array',
        'excluded_categories' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    public function affiliates()
    {
        return $this->hasMany(AffiliateCampaignAffiliate::class, 'campaign_id');
    }
}
