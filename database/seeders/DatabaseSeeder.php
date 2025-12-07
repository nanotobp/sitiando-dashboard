<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeders principales que siempre deben existir
        $this->call([
            RoleSeeder::class,          // crea roles base
            AdminUserSeeder::class,     // crea usuario SuperAdmin
            AbilitySeeder::class,       // crea abilities
            RoleAbilitySeeder::class,   // asigna abilities al SuperAdmin
            OrderSeeder::class,         // órdenes de prueba
            CartSeeder::class,          // 🛒 carritos + items de prueba
        ]);
    }
}
