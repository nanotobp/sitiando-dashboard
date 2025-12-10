<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Carbon;

class AffiliateClick extends Model
{
    use HasUuids;

    protected $table = 'affiliate_clicks';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'affiliate_id',
        'campaign_id',
        'product_id',

        'referral_code',
        'session_id',
        'cookie_value',

        'ip_address',
        'user_agent',
        'device_fingerprint',
        'referrer_url',
        'landing_page',
        'country_code',
        'city',

        // UTM
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',

        // Conversión
        'converted',
        'order_id',
        'converted_at',

        // Fraude
        'fraud_score',
        'is_flagged',
        'flag_reason',

        // Expiración
        'expires_at',

        // Tracking extra opcional
        'metadata',
    ];

    protected $casts = [
        'converted'    => 'boolean',
        'is_flagged'   => 'boolean',

        'expires_at'   => 'datetime',
        'converted_at' => 'datetime',

        'metadata'     => 'array',
    ];

    /* ==========================================================
       RELACIONES
    ========================================================== */

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

    public function commission()
    {
        return $this->hasOne(AffiliateCommission::class, 'click_id');
    }

    /* ==========================================================
       HELPERS PRO PARA TRACKING / FRAUDE / CONVERSIÓN
    ========================================================== */

    /**
     * Marca el clic como convertido.
     */
    public function markAsConverted(string $orderId)
    {
        $this->converted = true;
        $this->order_id = $orderId;
        $this->converted_at = now();
        $this->save();

        return $this;
    }

    /**
     * Deja registro de fraude.
     */
    public function flagFraud(string $reason, float $score = null)
    {
        $this->is_flagged = true;
        $this->flag_reason = $reason;
        $this->fraud_score = $score ?? $this->fraud_score;
        $this->save();

        return $this;
    }

    /**
     * Calcula fraude básico si no viene del Worker.
     */
    public function computeFraudScore()
    {
        $score = 0;

        if ($this->device_fingerprint === null) $score += 30;
        if ($this->ip_address === null)         $score += 20;
        if ($this->referrer_url === null)       $score += 10;

        $this->fraud_score = $score;
        $this->save();

        return $this->fraud_score;
    }

    /**
     * Determina si clic ya expiró para atribución.
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at
            ? Carbon::now()->greaterThan($this->expires_at)
            : false;
    }

    /**
     * Texto amigable para logs.
     */
    public function summary()
    {
        return sprintf(
            "Click %s (%s) — IP %s — %s %s",
            $this->id,
            $this->referral_code,
            $this->ip_address,
            $this->utm_source,
            $this->utm_campaign
        );
    }
}
