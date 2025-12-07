<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

use App\Models\Role;
use App\Models\Affiliate;
use App\Models\Order;
use App\Models\Cart;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids;

    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Ocultar campos sensibles
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts automáticos
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Un usuario puede tener varios roles
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    /**
     * Relación con afiliado (uno a uno)
     */
    public function affiliate()
    {
        return $this->hasOne(Affiliate::class);
    }

    /**
     * Relación con órdenes del ecommerce
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    /**
     * Relación con carritos
     */
    public function carts()
    {
        return $this->hasMany(Cart::class, 'user_id');
    }

    /**
     * Helper: verificar rol
     */
    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    /**
     * Helper: asignar rol
     */
    public function assignRole(string $roleName)
    {
        $role = Role::where('name', $roleName)->first();
        
        if ($role) {
            $this->roles()->syncWithoutDetaching([$role->id]);
        }
    }
}
