<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\OrderItem;
use App\Models\PaymentTransaction;
use App\Models\OrderStatusHistory;
use App\Models\AffiliateCommission;

class Order extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'orders';

    protected $fillable = [
        'id',
        'order_number',

        'customer_id',
        'customer_email',
        'customer_phone',

        'shipping_address',
        'billing_address',

        'subtotal',
        'discount_amount',
        'tax_amount',
        'shipping_amount',
        'total',

        'status',
        'metadata',
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'billing_address'  => 'array',
        'metadata'         => 'array',

        'subtotal'         => 'decimal:2',
        'discount_amount'  => 'decimal:2',
        'tax_amount'       => 'decimal:2',
        'shipping_amount'  => 'decimal:2',
        'total'            => 'decimal:2',
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

    public function commissions()
    {
        return $this->hasMany(AffiliateCommission::class, 'order_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function payments()
    {
        return $this->hasMany(PaymentTransaction::class, 'order_id')
            ->orderBy('created_at', 'desc');
    }

    public function latestPayment()
    {
        return $this->hasOne(PaymentTransaction::class, 'order_id')
            ->latest('created_at');
    }

    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class, 'order_id')
            ->orderBy('created_at', 'asc');
    }

    public function lastStatus()
    {
        return $this->hasOne(OrderStatusHistory::class, 'order_id')
            ->latest('created_at');
    }

    /* ==========================================================
       MÉTODOS ECOMMERCE
    ========================================================== */

    public function addItem($product, int $qty = 1)
    {
        if (is_numeric($product)) {
            $product = \App\Models\Product::findOrFail($product);
        }

        $price = $product->precio;

        $item = $this->items()->create([
            'product_id'   => $product->id,
            'product_name' => $product->name,
            'sku'          => $product->sku,
            'quantity'     => $qty,
            'unit_price'   => $price,
            'total_price'  => $price * $qty,
        ]);

        $this->recalculateTotals();

        return $item;
    }

    public function updateItem(string $itemId, int $qty)
    {
        $item = $this->items()->findOrFail($itemId);

        $item->quantity    = $qty;
        $item->total_price = $item->unit_price * $qty;
        $item->save();

        $this->recalculateTotals();
        return $item;
    }

    public function removeItem(string $itemId)
    {
        $item = $this->items()->findOrFail($itemId);
        $item->delete();

        $this->recalculateTotals();
    }

    public function recalculateTotals()
    {
        $subtotal = $this->items()->sum('total_price');

        $this->subtotal        = $subtotal;
        $this->discount_amount = $this->discount_amount ?? 0;
        $this->total           = ($subtotal - $this->discount_amount);

        $this->save();
    }
}
