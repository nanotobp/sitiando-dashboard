<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AffiliateClick extends Model
{
    use HasUuids;

    protected $table = 'affiliate_clicks';

    protected $fillable = [
        'affiliate_id',
        'referral_code',
        'product_id',
        'campaign_id',
        'ip_address',
        'user_agent',
        'device_fingerprint',
        'referrer_url',
        'landing_page',
        'session_id',
        'cookie_value',
        'expires_at',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'country_code',
        'city',
        'converted',
        'order_id',
        'converted_at',
        'fraud_score',
        'is_flagged',
        'flag_reason',
    ];

    protected $casts = [
        'converted' => 'boolean',
        'is_flagged' => 'boolean',
        'expires_at' => 'datetime',
        'converted_at' => 'datetime',
    ];

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class, 'affiliate_id');
    }

    public function campaign()
    {
        return $this->belongsTo(AffiliateCampaign::class, 'campaign_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
