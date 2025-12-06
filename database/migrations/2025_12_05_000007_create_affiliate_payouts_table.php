<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('affiliate_payouts', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('affiliate_id');

            // Período
            $table->date('period_start');
            $table->date('period_end');

            // Comisiones incluidas
            $table->json('commission_ids');

            // Montos
            $table->decimal('total_amount', 10, 2);
            $table->decimal('fee_amount', 10, 2)->default(0);
            $table->decimal('net_amount', 10, 2);

            // Método de pago
            $table->enum('payment_method', ['bank_transfer', 'paypal', 'cash'])->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_holder')->nullable();

            // Estado
            $table->enum('status', ['pending', 'processing', 'paid', 'failed', 'cancelled'])->default('pending');

            // Procesamiento
            $table->timestampTz('processed_at')->nullable();
            $table->uuid('processed_by')->nullable();
            $table->timestampTz('paid_at')->nullable();

            // Referencias externas
            $table->string('payment_reference')->nullable();
            $table->string('payment_proof_url')->nullable();

            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();

            $table->timestampsTz();

            $table->index('affiliate_id');
            $table->index('status');
            $table->index(['period_start', 'period_end']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_payouts');
    }
};
