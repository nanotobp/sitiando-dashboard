<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        /* =========================================================
           1) USERS → agregar columna temporal UUID
        ========================================================= */
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('uuid_tmp')->nullable();
        });

        DB::table('users')->update([
            'uuid_tmp' => DB::raw('gen_random_uuid()')
        ]);


        /* =========================================================
           2) USER_ROLES.user_id → convertir BIGINT → TEXT
        ========================================================= */

        Schema::table('user_roles', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        DB::statement('ALTER TABLE user_roles ALTER COLUMN user_id TYPE text;');


        /* =========================================================
           3) Actualizar user_roles.user_id con UUIDs reales
        ========================================================= */

        $users = DB::table('users')->select('id', 'uuid_tmp')->get();

        foreach ($users as $u) {
            DB::table('user_roles')
                ->where('user_id', strval($u->id))
                ->update(['user_id' => strval($u->uuid_tmp)]);
        }


        /* =========================================================
           4) Convertir TEXT → UUID en user_roles.user_id
        ========================================================= */

        DB::statement('ALTER TABLE user_roles ALTER COLUMN user_id TYPE uuid USING user_id::uuid;');


        /* =========================================================
           5) CONVERTIR USERS.id → UUID
        ========================================================= */

        // 5.1 eliminar primary key
        Schema::table('users', function (Blueprint $table) {
            $table->dropPrimary();
        });

        // 5.2 eliminar id BIGINT
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('id');
        });

        // 5.3 crear nueva columna id UUID **nullable**
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('id')->nullable();
        });

        // 5.4 copiar uuid_tmp → id
        DB::table('users')->update([
            'id' => DB::raw('uuid_tmp')
        ]);

        // 5.5 ahora que todos tienen valor, setear como NOT NULL + PK
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('id')->nullable(false)->change();
            $table->primary('id');
        });

        // 5.6 eliminar columna temporal
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('uuid_tmp');
        });


        /* =========================================================
           6) RECREAR FK
        ========================================================= */
        Schema::table('user_roles', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        throw new \Exception("Not reversible.");
    }
};
