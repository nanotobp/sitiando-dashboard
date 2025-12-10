<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Carbon;

class AffiliateCampaignAffiliate extends Model
{
    use HasUuids;

    protected $table = 'affiliate_campaign_affiliates';

    public $incrementing = false;
    protected $keyType   = 'string';

    protected $fillable = [
        'campaign_id',
        'affiliate_id',

        // Estado
        'approved',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_reason',

        // Fechas
        'joined_at',
        'expires_at',

        // Configuración personalizada
        'custom_commission_rate',
        'custom_payout_method',
        'custom_payout_details',

        // Metadata adicional del sistema
        'metadata',
    ];

    protected $casts = [
        'approved'     => 'boolean',
        'joined_at'    => 'datetime',
        'approved_at'  => 'datetime',
        'rejected_at'  => 'datetime',
        'expires_at'   => 'datetime',
        'metadata'     => 'array',
    ];

    /* ==========================================================
       RELACIONES
    ========================================================== */

    public function campaign()
    {
        return $this->belongsTo(AffiliateCampaign::class, 'campaign_id');
    }

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class, 'affiliate_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /* ==========================================================
       HELPERS PRO
    ========================================================== */

    public function approve($userId)
    {
        $this->approved = true;
        $this->approved_at = now();
        $this->approved_by = $userId;
        $this->save();

        return $this;
    }

    public function reject(string $reason)
    {
        $this->approved = false;
        $this->rejected_at = now();
        $this->rejected_reason = $reason;
        $this->save();

        return $this;
    }

    public function suspend(string $reason = null)
    {
        $this->approved = false;
        $this->rejected_at = now();
        $this->rejected_reason = $reason ?? 'Suspended by admin.';
        $this->save();

        return $this;
    }

    public function getIsExpiredAttribute()
    {
        return $this->expires_at
            ? Carbon::now()->greaterThan($this->expires_at)
            : false;
    }

    public function getEffectiveCommissionRateAttribute()
    {
        // Si afiliado tiene rate custom → usa ese
        if ($this->custom_commission_rate) {
            return $this->custom_commission_rate;
        }

        // Sino el rate de la campaña
        return $this->campaign->commission_rate;
    }

    public function summary()
    {
        return sprintf(
            "Affiliate %s en campaign %s — approved=%s",
            $this->affiliate_id,
            $this->campaign_id,
            $this->approved ? 'YES' : 'NO'
        );
    }
}
