<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AffiliatePayout extends Model
{
    use HasUuids;

    protected $table = 'affiliate_payouts';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'affiliate_id',
        'period_start',
        'period_end',
        'total_amount',
        'fee_amount',
        'net_amount',
        'status',
        'processed_at',
        'paid_at',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end'   => 'date',
        'processed_at' => 'datetime',
        'paid_at'      => 'datetime',
        'total_amount' => 'decimal:2',
        'fee_amount'   => 'decimal:2',
        'net_amount'   => 'decimal:2',
    ];

    /* ==========================================================
       RELACIONES
    ========================================================== */

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class, 'affiliate_id');
    }

    public function commissions()
    {
        return $this->hasMany(AffiliateCommission::class, 'payout_id');
    }

    /* ==========================================================
       MÉTODOS PRO
    ========================================================== */

    /** Monto total original sin fees */
    public function originalAmount()
    {
        return $this->total_amount;
    }

    /** Calcula fee + neto de forma segura */
    public function calculateNet()
    {
        $this->net_amount = $this->total_amount - $this->fee_amount;
        $this->save();
        return $this->net_amount;
    }

    /** Marcar como procesado */
    public function markProcessed($userId = null)
    {
        $this->status = 'processed';
        $this->processed_at = now();
        $this->save();
    }

    /** Marcar como pagado */
    public function markPaid($reference = null)
    {
        $this->status = 'paid';
        $this->paid_at = now();
        $this->save();
    }
}
