<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class CartItem extends Model
{
    use HasUuids;

    protected $table = 'cart_items';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'cart_id',
        'product_id',
        'variant_id',
        'quantity',
        'unit_price',
        'metadata',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'metadata'   => 'array',
    ];

    /* ==========================================================
       RELACIONES
    ========================================================== */

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    /* ==========================================================
       MÉTODOS PRO
    ========================================================== */

    public function subtotal()
    {
        return $this->quantity * $this->unit_price;
    }

    public function updateQuantity(int $qty)
    {
        $this->quantity = $qty;
        $this->save();

        // Recalcular subtotal en el carrito
        $this->cart?->recalculateTotals();

        return $this;
    }
}
