<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Order extends Model
{
    use HasUuids;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'order_number',

        'customer_name',
        'customer_email',
        'customer_phone',

        'subtotal',
        'discount',
        'total',

        'status',

        'payment_method',
        'payment_reference',
        'paid_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total'    => 'decimal:2',
        'paid_at'  => 'datetime',
    ];

    /* ==========================================================
       RELACIONES (AFILIADOS)
    ========================================================== */

    public function commissions()
    {
        return $this->hasMany(AffiliateCommission::class, 'order_id');
    }

    public function click()
    {
        return $this->hasOne(AffiliateClick::class, 'order_id');
    }

    /* ==========================================================
       ECOMMERCE PRO
    ========================================================== */

    // Items
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    // P A G O S  (compatibles con UUID)
    public function payments()
    {
        return $this->hasMany(OrderPayment::class, 'order_id')
                    ->orderBy('created_at', 'desc'); // FIX
    }

    // Último pago REAL
    public function latestPayment()
    {
        return $this->hasOne(OrderPayment::class, 'order_id')
                    ->latest('created_at'); // FIX total
    }

    // Historial de estado ordenado
    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class, 'order_id')
                    ->orderBy('created_at', 'asc');
    }

    // Último estado REAL
    public function lastStatus()
    {
        return $this->hasOne(OrderStatusHistory::class, 'order_id')
                    ->latest('created_at'); // FIX total
    }

    /* ==========================================================
       MÉTODOS
    ========================================================== */

    public function addItem($product, int $qty = 1)
    {
        if (is_numeric($product)) {
            $product = \App\Models\Product::findOrFail($product);
        }

        $price = $product->precio;

        $item = $this->items()->create([
            'product_id' => $product->id,
            'qty'        => $qty,
            'price'      => $price,
            'total'      => $price * $qty,
        ]);

        $this->recalculateTotals();

        return $item;
    }

    public function updateItem(string $itemId, int $qty)
    {
        $item = $this->items()->findOrFail($itemId);

        $item->qty   = $qty;
        $item->total = $item->price * $qty;
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
        $subtotal = $this->items()->sum('total');

        $this->subtotal = $subtotal;
        $this->discount = $this->discount ?? 0;
        $this->total    = $subtotal - $this->discount;

        $this->save();
    }
}
