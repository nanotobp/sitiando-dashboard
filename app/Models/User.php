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
     * UUID como clave primaria
     */
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'full_name',
        'email',
        'password',
        'role',
        'phone',
        'vendor_name',
        'vendor_code'
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

    /* RELACIONES */

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
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'user_id');
    }
}
