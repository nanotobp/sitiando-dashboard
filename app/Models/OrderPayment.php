<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class OrderPayment extends Model
{
    use HasUuids;

    protected $table = 'order_payments';

    protected $fillable = [
        'order_id',
        'status',
        'amount',
        'payment_method',
        'transaction_id',
        'gateway_response',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
        'paid_at' => 'datetime',
    ];

    /* ==========================================================
       RELACIONES
    ========================================================== */

    // La orden a la que pertenece este pago
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /* ==========================================================
       MÉTODOS ÚTILES PARA BANCARD
    ========================================================== */

    /**
     * Marca el pago como aprobado y actualiza la orden.
     */
    public function markAsPaid(array $gatewayPayload = [])
    {
        $this->status = 'paid';
        $this->gateway_response = $gatewayPayload;
        $this->paid_at = now();
        $this->save();

        // Actualizamos la orden
        $order = $this->order;
        $order->status = 'paid';
        $order->paid_at = now();
        $order->save();

        // Registrar en historial
        $order->statusHistory()->create([
            'status' => 'paid',
            'notes'  => 'Pago confirmado por Bancard',
        ]);

        return $this;
    }

    /**
     * Marca el pago como fallido
     */
    public function markAsFailed(array $gatewayPayload = [])
    {
        $this->status = 'failed';
        $this->gateway_response = $gatewayPayload;
        $this->save();

        // Actualizamos la orden
        $this->order->statusHistory()->create([
            'status' => 'failed',
            'notes'  => 'Pago rechazado',
        ]);

        return $this;
    }

    /**
     * Marca el pago como pendiente
     */
    public function markAsPending()
    {
        $this->status = 'pending';
        $this->save();

        $this->order->statusHistory()->create([
            'status' => 'pending',
            'notes'  => 'Esperando confirmación de Bancard',
        ]);

        return $this;
    }
}
