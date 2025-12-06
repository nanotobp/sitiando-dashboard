<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('affiliate_commissions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('affiliate_id');
            $table->uuid('order_id');
            $table->uuid('click_id')->nullable();
            $table->uuid('campaign_id')->nullable();

            // Cálculo de comisión
            $table->decimal('order_total', 10, 2);
            $table->decimal('commission_base', 10, 2);
            $table->decimal('commission_rate', 5, 2);
            $table->decimal('commission_amount', 10, 2);
            $table->enum('commission_type', ['sale', 'bonus', 'tiered', 'fixed']);

            // Estado
            $table->enum('status', ['pending', 'approved', 'rejected', 'paid'])->default('pending');

            // Aprobación
            $table->timestampTz('approved_at')->nullable();
            $table->uuid('approved_by')->nullable();

            // Rechazo
            $table->timestampTz('rejected_at')->nullable();
            $table->text('rejected_reason')->nullable();

            // Pago
            $table->timestampTz('paid_at')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();

            $table->timestampsTz();

            $table->index('affiliate_id');
            $table->index('order_id');
            $table->index('status');
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_commissions');
    }
};
