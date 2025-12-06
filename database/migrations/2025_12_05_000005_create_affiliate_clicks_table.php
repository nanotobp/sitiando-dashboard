<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('affiliate_clicks', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('affiliate_id');
            $table->string('referral_code');

            $table->uuid('product_id')->nullable();
            $table->uuid('campaign_id')->nullable();

            // Datos del visitante
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device_fingerprint')->nullable();
            $table->text('referrer_url')->nullable();
            $table->text('landing_page')->nullable();

            // UTM
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('utm_term')->nullable();
            $table->string('utm_content')->nullable();

            // Sesión / cookies
            $table->string('session_id');
            $table->string('cookie_value')->nullable();
            $table->timestampTz('expires_at')->nullable();

            // Geodata
            $table->string('country_code', 4)->nullable();
            $table->string('city')->nullable();

            // Conversión
            $table->boolean('converted')->default(false);
            $table->uuid('order_id')->nullable();
            $table->timestampTz('converted_at')->nullable();

            // Anti-fraude
            $table->unsignedInteger('fraud_score')->default(0);
            $table->boolean('is_flagged')->default(false);
            $table->text('flag_reason')->nullable();

            $table->timestampsTz();

            // Índices críticos
            $table->index('affiliate_id');
            $table->index('session_id');
            $table->index('referral_code');
            $table->index('utm_campaign');
            $table->index(['created_at']);
            $table->index('converted');
            $table->index('is_flagged');
            $table->index('ip_address');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_clicks');
    }
};
