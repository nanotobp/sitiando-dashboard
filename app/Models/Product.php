<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Product extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'products';

    protected $fillable = [
        'vendor_id',
        'category_id',
        'name',
        'slug',
        'sku',

        'description_short',
        'description_long',

        'base_price',
        'currency_code',

        'thumbnail_url',
        'gallery_urls',

        'manage_stock',
        'is_active',
        'is_featured',

        'tags',
        'metadata',
    ];

    protected $casts = [
        'gallery_urls' => 'array',
        'tags'         => 'array',
        'metadata'     => 'array',
        'manage_stock' => 'boolean',
        'is_active'    => 'boolean',
        'is_featured'  => 'boolean',
        'base_price'   => 'decimal:2',
    ];

    /* ==========================================================
       RELACIONES
    ========================================================== */

    // Vendedor dueño del producto
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    // Categoría del producto
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Items en órdenes asociadas
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    /* ==========================================================
       MÉTODOS ÚTILES
    ========================================================== */

    public function priceFormatted()
    {
        return number_format($this->base_price, 0, ',', '.');
    }

    public function isAvailable()
    {
        return $this->is_active;
    }

    public function mainImage()
    {
        return $this->thumbnail_url ?? ($this->gallery_urls[0] ?? null);
    }
}
