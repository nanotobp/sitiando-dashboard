<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class OrderItem extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'order_items';

    protected $fillable = [
        'id',
        'order_id',
        'product_id',
        'variant_id',
        'vendor_id',

        'product_name',
        'variant_name',
        'sku',

        'quantity',
        'unit_price',
        'total_price',

        'metadata',
    ];

    protected $casts = [
        'unit_price'  => 'decimal:2',
        'total_price' => 'decimal:2',
        'metadata'    => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    /* ==========================================================
       RELACIONES
    ========================================================== */

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    /* ==========================================================
       MÉTODOS DE CÁLCULO
    ========================================================== */

    public function recalc()
    {
        $this->total_price = $this->quantity * $this->unit_price;
        $this->save();

        $this->order->recalculateTotals();
        return $this;
    }

    public function updateQty(int $qty)
    {
        $this->quantity = $qty;
        return $this->recalc();
    }
}
