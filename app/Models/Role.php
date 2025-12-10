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

    /**
     * Verifica si el rol coincide con un nombre (reemplazo de is())
     * Debe evitar conflicto con Model::is()
     */
    public function isRole(string $roleName): bool
    {
        return $this->name === $roleName;
    }

    /**
     * Devuelve un texto bonito del rol
     */
    public function label(): string
    {
        return ucfirst($this->name);
    }

    /**
     * Verifica si el rol tiene una ability por key
     */
    public function hasAbility(string $abilityKey): bool
    {
        return $this->abilities()
            ->where('key', $abilityKey)
            ->exists();
    }

    /**
     * Devuelve todas las abilities en array plano
     */
    public function abilityKeys(): array
    {
        return $this->abilities()
            ->pluck('key')
            ->toArray();
    }
}
