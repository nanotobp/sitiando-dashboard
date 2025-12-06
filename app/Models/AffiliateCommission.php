<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AffiliateCommission extends Model
{
    use HasUuids;

    protected $table = 'affiliate_commissions';

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
        'order_total' => 'decimal:2',
        'commission_base' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

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
}
