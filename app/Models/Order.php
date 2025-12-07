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

        // Datos del cliente
        'customer_name',
        'customer_email',
        'customer_phone',

        // Totales
        'subtotal',
        'discount',
        'total',

        // Estado
        'status',

        // Pago
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
       RELACIONES (AFILIADOS Y TRACKING)
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
       RELACIONES ECOMMERCE PRO
    ========================================================== */

    // Items de la orden (producto + cantidad + precio)
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    // Pagos (Bancard u otros)
    public function payments()
    {
        return $this->hasMany(OrderPayment::class, 'order_id');
    }

    // Último pago registrado
    public function latestPayment()
    {
        return $this->hasOne(OrderPayment::class, 'order_id')->latestOfMany();
    }

    // Historial de estado de la orden
    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class, 'order_id')
                    ->orderBy('created_at', 'asc');
    }

    // Último estado registrado
    public function lastStatus()
    {
        return $this->hasOne(OrderStatusHistory::class, 'order_id')->latestOfMany();
    }

    /* ==========================================================
       MÉTODOS ECOMMERCE: addItem, recalculateTotals, etc.
    ========================================================== */

    /**
     * Agrega un item a la orden de manera segura y recalcula totales.
     * $product puede ser un modelo Product o un ID.
     */
    public function addItem($product, int $qty = 1)
    {
        // Si pasan ID, buscamos el modelo
        if (is_numeric($product)) {
            $product = \App\Models\Product::findOrFail($product);
        }

        // Precio del producto
        $price = $product->precio;

        // Crear ítem
        $item = $this->items()->create([
            'product_id' => $product->id,
            'qty'        => $qty,
            'price'      => $price,
            'total'      => $price * $qty,
        ]);

        // Recalcular totales de la orden
        $this->recalculateTotals();

        return $item;
    }

    /**
     * Actualiza la cantidad de un item existente.
     */
    public function updateItem(string $itemId, int $qty)
    {
        $item = $this->items()->findOrFail($itemId);

        $item->qty   = $qty;
        $item->total = $item->price * $qty;
        $item->save();

        $this->recalculateTotals();

        return $item;
    }

    /**
     * Elimina un item de la orden.
     */
    public function removeItem(string $itemId)
    {
        $item = $this->items()->findOrFail($itemId);
        $item->delete();

        $this->recalculateTotals();
    }

    /**
     * Recalcula subtotal, descuentos y total final.
     */
    public function recalculateTotals()
    {
        $subtotal = $this->items()->sum('total');

        $this->subtotal = $subtotal;
        $this->discount = $this->discount ?? 0;
        $this->total    = $subtotal - $this->discount;

        $this->save();
    }
}
