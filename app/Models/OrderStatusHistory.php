<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class OrderStatusHistory extends Model
{
    use HasUuids;

    protected $table = 'order_status_history';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'order_id',
        'old_status',
        'new_status',
        'changed_by',
        'changed_at',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    /* ==========================================================
       RELACIONES
    ========================================================== */

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    /* ==========================================================
       MÉTODO CENTRAL PARA REGISTRAR CAMBIOS
    ========================================================== */

    public static function record(Order $order, string $newStatus, ?string $userId = null)
    {
        return self::create([
            'order_id'   => $order->id,
            'old_status' => $order->status,
            'new_status' => $newStatus,
            'changed_by' => $userId,
            'changed_at' => now(),
        ]);
    }

    /* ==========================================================
       TEXTO RESUMIDO
    ========================================================== */

    public function formatted()
    {
        return sprintf(
            "[%s] %s → %s",
            $this->changed_at->format('d/m/Y H:i'),
            strtoupper($this->old_status ?? '-'),
            strtoupper($this->new_status ?? '-'),
        );
    }
}
