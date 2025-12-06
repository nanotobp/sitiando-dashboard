<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('affiliate_campaign_affiliates', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('campaign_id');
            $table->uuid('affiliate_id');

            $table->timestampTz('joined_at')->useCurrent();
            $table->boolean('approved')->default(true);

            $table->unique(['campaign_id', 'affiliate_id']);
            $table->index('campaign_id');
            $table->index('affiliate_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_campaign_affiliates');
    }
};
