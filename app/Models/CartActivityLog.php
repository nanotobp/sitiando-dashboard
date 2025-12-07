<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class CartActivityLog extends Model
{
    use HasUuids;

    protected $table = 'cart_activity_logs';

    protected $fillable = [
        'cart_id',
        'event',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
