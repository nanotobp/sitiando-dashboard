<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AffiliateFraudLog extends Model
{
    use HasUuids;

    protected $table = 'affiliate_fraud_logs';

    protected $fillable = [
        'affiliate_id',
        'click_id',
        'score',
        'reason',
        'ip_address',
        'user_agent',
        'fingerprint',
    ];

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class, 'affiliate_id');
    }

    public function click()
    {
        return $this->belongsTo(AffiliateClick::class, 'click_id');
    }
}
