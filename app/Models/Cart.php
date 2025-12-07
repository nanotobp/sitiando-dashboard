<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Cart extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'status',     // active, abandoned, completed
        'total',
    ];

    /**
     * Relación con el usuario dueño del carrito
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Items dentro del carrito
     */
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Logs de actividad del carrito
     */
    public function activity()
    {
        return $this->hasMany(CartActivityLog::class);
    }

    /**
     * Cantidad total de ítems del carrito
     */
    public function getItemsCountAttribute()
    {
        return $this->items()->sum('qty');
    }

    /**
     * Subtotal calculado por items
     */
    public function getSubtotalAttribute()
    {
        return $this->items()->sum('total');
    }
}
