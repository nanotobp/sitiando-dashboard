<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
    ];

    /**
     * Relación con users
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }

    /**
     * Relación con abilities
     */
    public function abilities()
    {
        return $this->belongsToMany(
            \App\Models\Ability::class,
            'role_abilities',
            'role_id',
            'ability_id'
        );
    }
}
