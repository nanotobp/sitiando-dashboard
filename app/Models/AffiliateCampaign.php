<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Carbon;

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

        'commission_type',           // percentage | fixed | hybrid
        'commission_rate',           // %
        'fixed_commission_amount',   // monto fijo
        'bonus_amount',              // bonos opcionales

        'included_products',
        'excluded_products',
        'included_categories',
        'excluded_categories',

        'utm_source',
        'utm_medium',
        'utm_campaign',

        'is_active',
        'visibility', // public | private

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

    /* ==========================================================
       RELACIONES PRINCIPALES
    ========================================================== */

    // Afiliados asignados a esta campaña (pivot completo)
    public function affiliates()
    {
        return $this->hasMany(AffiliateCampaignAffiliate::class, 'campaign_id');
    }

    // Solo afiliados aprobados
    public function affiliatesApproved()
    {
        return $this->affiliates()->where('approved', true);
    }

    // Afiliados pendientes de aprobación
    public function affiliatesPending()
    {
        return $this->affiliates()->whereNull('approved');
    }

    // Clics generados por esta campaña
    public function clicks()
    {
        return $this->hasMany(AffiliateClick::class, 'campaign_id');
    }

    // Comisiones generadas por esta campaña
    public function commissions()
    {
        return $this->hasMany(AffiliateCommission::class, 'campaign_id');
    }

    // Archivos (media kit)
    public function assets()
    {
        return $this->hasMany(MediaKitAsset::class, 'campaign_id');
    }

    /* ==========================================================
       HELPERS PRO PARA DASHBOARD
    ========================================================== */

    public function isActive()
    {
        return $this->is_active === true;
    }

    public function isExpired()
    {
        return $this->end_date ? Carbon::now()->greaterThan($this->end_date) : false;
    }

    public function isLive()
    {
        return !$this->isExpired() && $this->isActive();
    }

    // Devuelve la comisión real aplicable
    public function effectiveRate()
    {
        if ($this->commission_type === 'fixed') {
            return $this->fixed_commission_amount;
        }

        if ($this->commission_type === 'percentage') {
            return $this->commission_rate;
        }

        if ($this->commission_type === 'hybrid') {
            return [
                'percentage' => $this->commission_rate,
                'fixed'      => $this->fixed_commission_amount,
            ];
        }

        return null;
    }

    // Para reportes rápidos
    public function summary()
    {
        return [
            'campaign'      => $this->name,
            'active'        => $this->isActive(),
            'expired'       => $this->isExpired(),
            'affiliates'    => $this->affiliatesApproved()->count(),
            'clicks'        => $this->clicks()->count(),
            'conversions'   => $this->commissions()->count(),
        ];
    }

    public function scopeVisible($query)
    {
        return $query->where('visibility', 'public')->where('is_active', true);
    }
}
