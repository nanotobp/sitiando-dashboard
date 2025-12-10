<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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

        'joined_at' => 'datetime',
        'approved_at' => 'datetime',
        'suspended_at' => 'datetime',
        'last_sale_at' => 'datetime',

        'metadata' => 'array',
    ];

    /* ==========================================================
       RELACIONES PRINCIPALES
    ========================================================== */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

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
        return $this->hasMany(AffiliateTier::class, 'affiliate_id')->orderBy('level');
    }

    public function campaignMemberships()
    {
        return $this->hasMany(AffiliateCampaignAffiliate::class, 'affiliate_id');
    }

    public function approvedCampaigns()
    {
        return $this->campaignMemberships()->where('approved', true);
    }

    /* ==========================================================
       SCOPES PRO
    ========================================================== */

    public function scopeActive($q)
    {
        return $q->where('is_active', true)->whereNull('suspended_at');
    }

    public function scopeSuspended($q)
    {
        return $q->whereNotNull('suspended_at');
    }

    public function scopePendingApproval($q)
    {
        return $q->whereNull('approved_at');
    }

    /* ==========================================================
       ESTADO DEL AFILIADO
    ========================================================== */

    public function isApproved()
    {
        return $this->approved_at !== null;
    }

    public function isSuspended()
    {
        return $this->suspended_at !== null;
    }

    public function isLive()
    {
        return $this->is_active && !$this->isSuspended() && $this->isApproved();
    }

    /* ==========================================================
       MÉTRICAS PRO — DASHBOARD
    ========================================================== */

    public function clicksCount()
    {
        return $this->clicks()->count();
    }

    public function conversionsCount()
    {
        return $this->commissions()->count();
    }

    public function totalCommissionEarned()
    {
        return $this->commissions()->sum('commission_amount');
    }

    public function totalPaid()
    {
        return $this->payouts()->sum('net_amount');
    }

    public function pendingCommission()
    {
        return $this->commissions()
            ->where('status', 'approved')
            ->whereNull('paid_at')
            ->sum('commission_amount');
    }

    public function performanceSummary()
    {
        return [
            'clicks'        => $this->clicksCount(),
            'conversions'   => $this->conversionsCount(),
            'commission'    => $this->totalCommissionEarned(),
            'paid'          => $this->totalPaid(),
            'pending'       => $this->pendingCommission(),
            'cr'            => $this->calculateConversionRate(),
            'aov'           => $this->calculateAOV(),
        ];
    }

    public function calculateConversionRate()
    {
        $clicks = $this->clicksCount();
        if ($clicks == 0) return 0;

        return round(($this->conversionsCount() / $clicks) * 100, 2);
    }

    public function calculateAOV()
    {
        return $this->commissions()->avg('order_total') ?? 0;
    }

    /* ==========================================================
       TIER SYSTEM — PROGRESIÓN DE NIVELES
    ========================================================== */

    public function currentTier()
    {
        return $this->tiers()
            ->where('min_sales', '<=', $this->total_sales)
            ->where('max_sales', '>=', $this->total_sales)
            ->first();
    }

    public function nextTier()
    {
        return $this->tiers()
            ->where('min_sales', '>', $this->total_sales)
            ->orderBy('min_sales')
            ->first();
    }

    /* ==========================================================
       COMISIONES DINÁMICAS
    ========================================================== */

    public function effectiveCommissionRate()
    {
        // Tier override
        if ($tier = $this->currentTier()) {
            return $tier->commission_rate;
        }

        // Affiliate-specific setting
        if ($this->commission_rate) {
            return $this->commission_rate;
        }

        return null;
    }

    public function effectiveFixedCommission()
    {
        return $this->fixed_commission_amount ?? 0;
    }

    /* ==========================================================
       HELPERS MISC
    ========================================================== */

    public function fullDisplayName()
    {
        return $this->business_name ?: $this->full_name ?: $this->email;
    }
}
