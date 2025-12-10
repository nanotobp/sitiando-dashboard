<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class CartActivityLog extends Model
{
    use HasUuids;

    protected $table = 'cart_activity_logs';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'cart_id',
        'event',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /* ==========================================================
       RELACIONES
    ========================================================== */

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /* ==========================================================
       MÉTODOS ÚTILES
    ========================================================== */

    /**
     * Registrar un evento en el carrito.
     */
    public static function record(string $cartId, string $event, array $payload = [])
    {
        return self::create([
            'cart_id' => $cartId,
            'event'   => $event,
            'payload' => $payload,
        ]);
    }

    /**
     * Resumen legible.
     */
    public function formatted()
    {
        return sprintf(
            "[%s] %s → %s",
            $this->created_at->format('d/m/Y H:i'),
            strtoupper($this->event),
            json_encode($this->payload)
        );
    }
}
