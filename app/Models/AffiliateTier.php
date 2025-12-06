<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AffiliateTier extends Model
{
    use HasUuids;

    protected $table = 'affiliate_tiers';

    protected $fillable = [
        'affiliate_id',
        'level',
        'min_sales',
        'max_sales',
        'commission_rate',
    ];

    protected $casts = [
        'level' => 'integer',
        'min_sales' => 'integer',
        'max_sales' => 'integer',
        'commission_rate' => 'decimal:2',
    ];

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class, 'affiliate_id');
    }
}
