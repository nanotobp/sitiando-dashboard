<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Crea el usuario admin si no existe
        $user = User::firstOrCreate(
            ['email' => 'admin@sitiando.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123')
            ]
        );

        // Asignar el rol admin
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $user->roles()->syncWithoutDetaching([$adminRole->id]);
        }
    }
}
