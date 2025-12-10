<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

use App\Models\Role;
use App\Models\Ability;
use App\Models\Affiliate;
use App\Models\Order;
use App\Models\Cart;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'full_name',
        'email',
        'password',
        'phone',
        'vendor_name',
        'vendor_code',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    /* ==========================================================
       RELACIONES
    ========================================================== */

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

    /* ==========================================================
       HELPERS
    ========================================================== */

    public function getNameAttribute()
    {
        return $this->full_name ?? $this->email;
    }

    /* ==========================================================
       ROLES — Saneados y 100% compatibles
    ========================================================== */

    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function hasAnyRole(...$roles): bool
    {
        // Acepta array o argumentos separados
        $roles = is_array($roles[0]) ? $roles[0] : $roles;

        return $this->roles()->whereIn('name', $roles)->exists();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /* ==========================================================
       ABILITIES — Optimizado
    ========================================================== */

    public function abilities()
    {
        return Ability::whereIn('abilities.id',
            $this->roles()
                ->with('abilities')
                ->get()
                ->pluck('abilities')
                ->flatten()
                ->pluck('id')
        )->get();
    }

    public function hasAbility(string $key): bool
    {
        return $this->abilities()->contains('key', $key);
    }

    public function canAccess($required)
    {
        if ($this->isAdmin()) return true;

        if (is_string($required)) {
            return $this->hasAbility($required);
        }

        if (is_array($required)) {
            foreach ($required as $ability) {
                if ($this->hasAbility($ability)) {
                    return true;
                }
            }
        }

        return false;
    }
}
