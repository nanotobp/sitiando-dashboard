<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Ability;

class RoleAbilitySeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::where('name', 'SuperAdmin')->first();

        if (!$superAdmin) {
            echo "⚠️ No existe el rol SuperAdmin. Crealo antes de correr este seeder.\n";
            return;
        }

        $abilities = Ability::pluck('id')->toArray();

        $superAdmin->abilities()->sync($abilities);

        echo "✔ SuperAdmin recibió " . count($abilities) . " abilities.\n";
    }
}
