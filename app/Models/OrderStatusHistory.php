<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class OrderStatusHistory extends Model
{
    use HasUuids;

    protected $table = 'order_status_history';

    protected $fillable = [
        'order_id',
        'status',
        'notes',
        'changed_by', // opcional: usuario admin/operador
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /* ==========================================================
       RELACIONES
    ========================================================== */

    // La orden cuyos cambios se registran
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Usuario que realizó el cambio (si corresponde)
    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }


    /* ==========================================================
       MÉTODOS ÚTILES
    ========================================================== */

    /**
     * Crear una entrada en el historial y actualizar la orden.
     * Esto se usa en controladores y servicios.
     */
    public static function recordStatus(Order $order, string $newStatus, string $notes = null, $userId = null)
    {
        // 1) Registrar historial
        $record = self::create([
            'order_id'   => $order->id,
            'status'     => $newStatus,
            'notes'      => $notes,
            'changed_by' => $userId,
        ]);

        // 2) Actualizar orden
        $order->status = $newStatus;
        $order->save();

        return $record;
    }

    /**
     * Devuelve un texto resumen amigable
     */
    public function formatted()
    {
        return sprintf(
            "[%s] %s — %s",
            $this->created_at->format('d/m/Y H:i'),
            strtoupper($this->status),
            $this->notes ?? 'Sin notas'
        );
    }
}
