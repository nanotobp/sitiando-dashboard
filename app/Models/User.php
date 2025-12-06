<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\Role;
use App\Models\Affiliate;
use App\Models\Order;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Ocultar campos sensibles en arrays / JSON
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
        'password' => 'hashed', // Laravel 10+: hashea automáticamente
    ];

    /**
     * Un usuario puede tener varios roles
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    /**
     * Relación uno a uno con afiliado
     * (solo para usuarios que sean vendedores/afiliados)
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
        return $this->hasMany(Order::class);
    }

    /**
     * Helper: verificar si usuario tiene un rol
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
