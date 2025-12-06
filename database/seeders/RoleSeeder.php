<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::insert([
            [
                'name' => 'admin',
                'description' => 'Administrador del sistema',
            ],
            [
                'name' => 'operador',
                'description' => 'Operador del panel',
            ],
            [
                'name' => 'vendedor',
                'description' => 'Gestión de ventas',
            ],
        ]);
    }
}
