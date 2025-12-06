<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UtmTracking extends Model
{
    use HasUuids;

    protected $table = 'utm_tracking';

    protected $fillable = [
        'click_id',
        'order_id',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
    ];

    public function click()
    {
        return $this->belongsTo(AffiliateClick::class, 'click_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
