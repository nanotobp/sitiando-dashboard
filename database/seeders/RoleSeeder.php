<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'superadmin',
                'description' => 'Control total del sistema Sitiando',
            ],
            [
                'name' => 'admin',
                'description' => 'Administración avanzada del comercio',
            ],
            [
                'name' => 'operador',
                'description' => 'Gestión operativa del panel',
            ],
            [
                'name' => 'vendedor',
                'description' => 'Manejo de ventas y tickets',
            ],
            [
                'name' => 'afiliado',
                'description' => 'Acceso al panel de comisiones y referidos',
            ],
            [
                'name' => 'analista',
                'description' => 'Análisis de informes y reportes',
            ],
        ];

        foreach ($roles as $data) {
            Role::updateOrCreate(
                ['name' => $data['name']],  // lookup
                ['description' => $data['description']] // update if exists
            );
        }
    }
}
