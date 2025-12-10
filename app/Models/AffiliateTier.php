<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AffiliateTier extends Model
{
    use HasUuids;

    protected $table = 'affiliate_tiers';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'affiliate_id',
        'level',
        'min_sales',
        'max_sales',
        'commission_rate',
    ];

    protected $casts = [
        'level'           => 'integer',
        'min_sales'       => 'decimal:2',
        'max_sales'       => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
    ];

    /* ============================================
       RELACIONES
    ============================================ */

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class, 'affiliate_id');
    }

    /* ============================================
       MÉTODOS ÚTILES
    ============================================ */

    /**
     * Devuelve true si las ventas están dentro del rango del tier.
     */
    public function matchesSales(float $sales)
    {
        return $sales >= $this->min_sales &&
               ($this->max_sales === null || $sales <= $this->max_sales);
    }

    /**
     * Devuelve el porcentaje en formato usable (ej: 0.15)
     */
    public function rate()
    {
        return $this->commission_rate / 100;
    }

    /**
     * Scope para ordenar correctamente por nivel.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('level', 'asc');
    }
}
