<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('media_kit_assets', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');
            $table->text('description')->nullable();
            $table->text('file_url');
            $table->text('thumbnail_url')->nullable();
            $table->string('file_type', 20)->nullable(); // jpg, png, zip, mp4, pdf
            $table->json('tags')->nullable();

            $table->uuid('campaign_id')->nullable();

            // Métricas
            $table->unsignedBigInteger('downloads')->default(0);
            $table->unsignedBigInteger('impressions')->default(0);

            $table->timestampsTz();

            $table->index('campaign_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_kit_assets');
    }
};
