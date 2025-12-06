<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('affiliate_fraud_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('affiliate_id');
            $table->uuid('click_id');

            $table->unsignedInteger('score');
            $table->text('reason');

            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('fingerprint')->nullable();

            $table->timestampsTz();

            $table->index('affiliate_id');
            $table->index('click_id');
            $table->index(['score']);
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_fraud_logs');
    }
};
