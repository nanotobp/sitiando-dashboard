<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
    protected $table = 'abilities';

    protected $fillable = [
        'key',          // Ej: "orders.view"
        'label',        // Ej: "Ver Órdenes"
        'description',  // Explicación interna
    ];

    /* ==========================================================
       RELACIONES
    ========================================================== */

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_abilities');
    }

    /* ==========================================================
       SCOPES PRO
    ========================================================== */

    public function scopeByKey($query, string $key)
    {
        return $query->where('key', $key);
    }

    public function scopeSearch($query, string $q = null)
    {
        if (!$q) return $query;

        return $query->where(function ($x) use ($q) {
            $x->where('key', 'ILIKE', "%{$q}%")
              ->orWhere('label', 'ILIKE', "%{$q}%")
              ->orWhere('description', 'ILIKE', "%{$q}%");
        });
    }

    /* ==========================================================
       HELPERS — CALIDAD DE VIDA
    ========================================================== */

    /**
     * Devuelve nombre + etiqueta bonita
     * Ej: "orders.view — Ver Órdenes"
     */
    public function pretty()
    {
        return "{$this->key} — {$this->label}";
    }

    /**
     * Devuelve true si un rol en particular contiene esta ability.
     */
    public function roleHasAbility($roleId)
    {
        return $this->roles()->where('roles.id', $roleId)->exists();
    }
}
