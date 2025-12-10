<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Carbon;

class AffiliateCommission extends Model
{
    use HasUuids;

    protected $table = 'affiliate_commissions';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'affiliate_id',
        'order_id',
        'click_id',
        'campaign_id',

        'order_total',
        'commission_base',
        'commission_rate',
        'commission_amount',
        'commission_type',

        'status',

        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_reason',

        'paid_at',
        'payment_method',
        'payment_reference',
    ];

    protected $casts = [
        'order_total'        => 'decimal:2',
        'commission_base'    => 'decimal:2',
        'commission_rate'    => 'decimal:2',
        'commission_amount'  => 'decimal:2',

        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'paid_at'     => 'datetime',

        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /* ======================================
       RELACIONES
    ====================================== */

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class, 'affiliate_id');
    }

    public function click()
    {
        return $this->belongsTo(AffiliateClick::class, 'click_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function campaign()
    {
        return $this->belongsTo(AffiliateCampaign::class, 'campaign_id');
    }

    /* ======================================
       MÉTODOS PRO — Lógica de negocio
    ====================================== */

    /**
     * Aprueba esta comisión.
     */
    public function approve($userId = null)
    {
        $this->status = 'approved';
        $this->approved_by = $userId;
        $this->approved_at = now();
        $this->save();

        return $this;
    }

    /**
     * Rechaza esta comisión.
     */
    public function reject(string $reason, $userId = null)
    {
        $this->status = 'rejected';
        $this->rejected_reason = $reason;
        $this->rejected_at = now();
        $this->approved_by = $userId;
        $this->save();

        return $this;
    }

    /**
     * Marca como pagada.
     */
    public function markAsPaid(string $method = 'manual', ?string $reference = null)
    {
        $this->status = 'paid';
        $this->payment_method = $method;
        $this->payment_reference = $reference;
        $this->paid_at = now();
        $this->save();

        return $this;
    }

    /**
     * Calcula comisión real.
     */
    public function calculateAmount()
    {
        $this->commission_amount = round($this->commission_base * ($this->commission_rate / 100), 2);
        return $this->commission_amount;
    }

    /**
     * Muestra resumen amigable.
     */
    public function summary()
    {
        return sprintf(
            "Order %s → %s%% → %s PYG (%s)",
            $this->order_number ?? $this->order_id,
            $this->commission_rate,
            $this->commission_amount,
            strtoupper($this->status)
        );
    }
}
