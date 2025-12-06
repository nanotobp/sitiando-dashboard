<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->uuid('affiliate_id')->nullable()->after('id');
            $table->string('referral_code')->nullable()->after('affiliate_id');
            $table->uuid('affiliate_click_id')->nullable()->after('referral_code');
            $table->string('utm_campaign')->nullable()->after('affiliate_click_id');

            $table->index('affiliate_id');
            $table->index('referral_code');
            $table->index('affiliate_click_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['affiliate_id']);
            $table->dropIndex(['referral_code']);
            $table->dropIndex(['affiliate_click_id']);

            $table->dropColumn(['affiliate_id', 'referral_code', 'affiliate_click_id', 'utm_campaign']);
        });
    }
};
