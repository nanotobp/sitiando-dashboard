<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// ← Quitamos HasUuids porque ya NO queremos UUID
// use Illuminate\Database\Eloquent\Concerns\HasUuids;

use App\Models\Role;
use App\Models\Affiliate;
use App\Models\Order;
use App\Models\Cart;

class User extends Authenticatable
{
    use HasFactory, Notifiable; // ← HasUuids eliminado

    /**
     * Forzamos que use IDs enteros autoincrementales (bigIncrements)
     * Esto soluciona el error "invalid input syntax for type uuid: 7"
     */
    public $incrementing = true;           // ← Antes era false
    protected $keyType = 'int';            // ← Antes era 'string'

    /**
     * Atributos asignables masivamente.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Atributos ocultos en serialización.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts modernos (Laravel 10+ style)
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'id' => 'integer', // ← Recomendado para reforzar que es entero
        ];
    }

    /* ===========================================================
       RELACIONES PARA SITIANDO PRO (Marketplace + Afiliados + Roles)
       =========================================================== */

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function affiliate()
    {
        return $this->hasOne(Affiliate::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'user_id');
    }

    /* ===========================================================
       HELPERS PRO
       =========================================================== */

    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function assignRole(string $roleName)
    {
        $role = Role::where('name', $roleName)->first();

        if ($role) {
            $this->roles()->syncWithoutDetaching([$role->id]);
        }
    }
}