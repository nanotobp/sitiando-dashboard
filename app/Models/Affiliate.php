<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Affiliate extends Model
{
    use HasUuids;

    protected $table = 'affiliates';

    protected $fillable = [
        'user_id',
        'affiliate_code',
        'referral_code',
        'business_name',
        'full_name',
        'phone',
        'email',
        'tax_id',
        'tax_id_type',
        'bank_name',
        'account_number',
        'account_type',
        'account_holder_name',
        'commission_rate',
        'commission_type',
        'fixed_commission_amount',
        'allowed_categories',
        'excluded_products',
        'total_clicks',
        'total_conversions',
        'total_sales',
        'total_commission_earned',
        'total_commission_paid',
        'pending_commission',
        'conversion_rate',
        'average_order_value',
        'status',
        'is_active',
        'terms_accepted',
        'terms_accepted_at',
        'terms_version',
        'joined_at',
        'approved_at',
        'suspended_at',
        'last_sale_at',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'allowed_categories' => 'array',
        'excluded_products' => 'array',
        'is_active' => 'boolean',
        'terms_accepted' => 'boolean',
        'metadata' => 'array',
        'joined_at' => 'datetime',
        'approved_at' => 'datetime',
        'suspended_at' => 'datetime',
        'last_sale_at' => 'datetime',
    ];

    // Relaciones
    public function clicks()
    {
        return $this->hasMany(AffiliateClick::class, 'affiliate_id');
    }

    public function commissions()
    {
        return $this->hasMany(AffiliateCommission::class, 'affiliate_id');
    }

    public function payouts()
    {
        return $this->hasMany(AffiliatePayout::class, 'affiliate_id');
    }

    public function tiers()
    {
        return $this->hasMany(AffiliateTier::class, 'affiliate_id');
    }

    public function campaignMemberships()
    {
        return $this->hasMany(AffiliateCampaignAffiliate::class, 'affiliate_id');
    }
}
