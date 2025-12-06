<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('affiliate_campaigns', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');
            $table->text('description')->nullable();
            $table->string('slug')->unique();

            // Ventana de tiempo
            $table->timestampTz('start_date');
            $table->timestampTz('end_date')->nullable();

            // Reglas de comisión
            $table->enum('commission_type', ['percentage', 'fixed', 'tiered', 'bonus'])->default('percentage');
            $table->decimal('commission_rate', 5, 2)->nullable();
            $table->decimal('fixed_commission_amount', 10, 2)->nullable();
            $table->decimal('bonus_amount', 10, 2)->nullable();

            // Filtros de productos / categorías (UUIDs como json)
            $table->json('included_products')->nullable();
            $table->json('excluded_products')->nullable();
            $table->json('included_categories')->nullable();
            $table->json('excluded_categories')->nullable();

            // UTM defaults
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();

            // Estado
            $table->boolean('is_active')->default(true);
            $table->enum('visibility', ['public', 'private'])->default('public');

            $table->timestampsTz();

            $table->index('is_active');
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_campaigns');
    }
};
