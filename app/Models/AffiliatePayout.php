<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AffiliatePayout extends Model
{
    use HasUuids;

    protected $table = 'affiliate_payouts';

    protected $fillable = [
        'affiliate_id',
        'period_start',
        'period_end',
        'commission_ids',
        'total_amount',
        'fee_amount',
        'net_amount',
        'payment_method',
        'bank_name',
        'account_number',
        'account_holder',
        'status',
        'processed_at',
        'processed_by',
        'paid_at',
        'payment_reference',
        'payment_proof_url',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'commission_ids' => 'array',
        'period_start' => 'date',
        'period_end' => 'date',
        'processed_at' => 'datetime',
        'paid_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class, 'affiliate_id');
    }
}
