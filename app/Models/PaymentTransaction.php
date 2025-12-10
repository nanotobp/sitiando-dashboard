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
        'amount'            => 'decimal:2',
        'gateway_response'  => 'array',
        'paid_at'           => 'datetime',
    ];

    /* ==========================================================
       RELACIÓN PRINCIPAL
    ========================================================== */

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /* ==========================================================
       FLUJO REAL PARA BANCARD
    ========================================================== */

    public function logStatusChange(string $newStatus, string $note = null)
    {
        $order = $this->order;

        return $order->statusHistory()->create([
            'status'     => $newStatus,
            'notes'      => $note,
            'changed_by' => auth()->id(),
        ]);
    }

    public function markAsApproved(array $payload = [])
    {
        $this->update([
            'status'           => 'approved',
            'gateway_response' => $payload,
            'paid_at'          => now(),
        ]);

        $this->order->update(['status' => 'paid']);

        $this->logStatusChange('paid', 'Pago aprobado por Bancard');

        return $this;
    }

    public function markAsAuthorized(array $payload = [])
    {
        $this->update([
            'status'           => 'authorized',
            'gateway_response' => $payload,
        ]);

        $this->logStatusChange('authorized', 'Pago autorizado por Bancard');

        return $this;
    }

    public function markAsRejected(array $payload = [])
    {
        $this->update([
            'status'           => 'rejected',
            'gateway_response' => $payload,
        ]);

        $this->logStatusChange('failed', 'Pago rechazado');

        return $this;
    }

    public function markAsPending()
    {
        $this->update(['status' => 'pending']);

        $this->logStatusChange('pending', 'Pago pendiente');

        return $this;
    }
}
