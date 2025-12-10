<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Cart extends Model
{
    use HasUuids;

    protected $table = 'carts';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'session_id',
        'status',            // active, abandoned, completed (enum PG)
        'last_activity_at',
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
    ];

    /* ==========================================================
       RELACIONES
    ========================================================== */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class, 'cart_id');
    }

    public function activity()
    {
        return $this->hasMany(CartActivityLog::class, 'cart_id')
                    ->orderBy('created_at', 'desc');
    }

    /* ==========================================================
       ATRIBUTOS CALCULADOS (SUBTOTAL / TOTAL ITEMS)
    ========================================================== */

    // Cantidad total de ítems (suma de quantities)
    public function getItemsCountAttribute()
    {
        return $this->items()->sum('quantity');
    }

    // Subtotal calculado dinámicamente
    public function getSubtotalAttribute()
    {
        return $this->items()
            ->selectRaw('SUM(quantity * unit_price) as subtotal')
            ->value('subtotal') ?? 0;
    }

    // Total: PRO → igual a subtotal (por ahora)
    public function getTotalAttribute()
    {
        return $this->subtotal;
    }

    /* ==========================================================
       MÉTODOS PRO
    ========================================================== */

    /**
     * Recalcula totales llamando a las propiedades dinámicas.
     */
    public function recalculateTotals()
    {
        // Esto permite compatibilidad con controladores/servicios
        $this->total_cached = $this->total; // si más adelante agregás columna real
        $this->last_activity_at = now();
        $this->save();

        return $this->total;
    }

    /**
     * Marca actividad en el carrito
     */
    public function touchActivity(string $event, array $payload = [])
    {
        $this->last_activity_at = now();
        $this->save();

        $this->activity()->create([
            'event'   => $event,
            'payload' => $payload,
        ]);
    }

    /**
     * Estado lógico: el carrito se considera abandonado.
     */
    public function markAsAbandoned()
    {
        $this->status = 'abandoned';
        $this->save();
    }
}
