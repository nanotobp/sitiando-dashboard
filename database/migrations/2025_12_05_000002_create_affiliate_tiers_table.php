<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('affiliate_tiers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('affiliate_id');
            $table->unsignedInteger('level');
            $table->unsignedInteger('min_sales');
            $table->unsignedInteger('max_sales')->nullable();
            $table->decimal('commission_rate', 5, 2);

            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->nullable();

            $table->unique(['affiliate_id', 'level']);
            $table->index('affiliate_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_tiers');
    }
};
