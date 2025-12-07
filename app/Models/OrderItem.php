<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class OrderItem extends Model
{
    use HasUuids;

    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'qty',
        'price',
        'total',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /* ==========================================================
       RELACIONES
    ========================================================== */

    // La orden a la que pertenece este ítem
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Producto original asociado
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /* ==========================================================
       MÉTODOS ÚTILES
    ========================================================== */

    /**
     * Recalcula el total del item (qty * price).
     * Luego delega el recalculo total de la orden.
     */
    public function recalc()
    {
        $this->total = $this->qty * $this->price;
        $this->save();

        // Recalcula la orden completa:
        $this->order->recalculateTotals();

        return $this;
    }

    /**
     * Actualiza la cantidad del ítem y recalcula.
     */
    public function updateQty(int $qty)
    {
        $this->qty = $qty;
        return $this->recalc();
    }
}
