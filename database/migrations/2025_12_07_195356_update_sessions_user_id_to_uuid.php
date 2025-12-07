<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Eliminar la columna vieja si existe
        if (Schema::hasColumn('sessions', 'user_id')) {
            Schema::table('sessions', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }

        // 2) Crear la columna UUID
        Schema::table('sessions', function (Blueprint $table) {
            $table->uuid('user_id')->nullable()->index();
        });
    }

    public function down(): void
    {
        // revertir: borrar uuid
        if (Schema::hasColumn('sessions', 'user_id')) {
            Schema::table('sessions', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }

        // recrear BIGINT si hiciera falta
        Schema::table('sessions', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->index();
        });
    }
};
