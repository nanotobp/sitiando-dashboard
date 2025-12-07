<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'nombre',
        'sku',
        'precio',
        'stock',
        'imagen',
        'activo',
    ];

    /* ==========================================================
       RELACIÓN: un producto puede aparecer en varios order_items
    ========================================================== */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }
}
