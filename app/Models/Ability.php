<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
    protected $fillable = [
        'key',
        'label',
        'description',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_abilities');
    }
}
