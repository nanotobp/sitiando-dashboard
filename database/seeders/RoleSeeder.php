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
                'name' => 'SuperAdmin',
                'description' => 'Control total del sistema Sitiando',
            ],
            [
                'name' => 'Admin',
                'description' => 'Administración avanzada del comercio',
            ],
            [
                'name' => 'Operador',
                'description' => 'Gestión operativa del panel',
            ],
            [
                'name' => 'VendedorExterno',
                'description' => 'Manejo de ventas y tickets',
            ],
            [
                'name' => 'Afiliado',
                'description' => 'Acceso al panel de comisiones y referidos',
            ],
            [
                'name' => 'Analista',
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
