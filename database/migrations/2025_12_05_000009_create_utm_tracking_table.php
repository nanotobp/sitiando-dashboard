<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('utm_tracking', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('click_id');
            $table->uuid('order_id')->nullable();

            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('utm_term')->nullable();
            $table->string('utm_content')->nullable();

            $table->timestampsTz();

            $table->index('click_id');
            $table->index('order_id');
            $table->index('utm_campaign');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('utm_tracking');
    }
};
