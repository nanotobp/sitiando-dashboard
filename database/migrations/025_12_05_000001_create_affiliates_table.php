<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('affiliates', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Relación con tabla users (opcional / futura)
            $table->uuid('user_id')->nullable()->unique();

            // Códigos únicos
            $table->string('affiliate_code')->unique();   // interno (AFF-0001)
            $table->string('referral_code')->unique();    // público (JUAN2025)

            // Datos principales
            $table->string('business_name')->nullable();
            $table->string('full_name');
            $table->string('phone');
            $table->string('email');

            // Datos fiscales
            $table->string('tax_id')->nullable();
            $table->enum('tax_id_type', ['RUC', 'CI'])->nullable();

            // Datos bancarios
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->enum('account_type', ['savings', 'checking'])->nullable();
            $table->string('account_holder_name')->nullable();

            // Comisiones base
            $table->decimal('commission_rate', 5, 2)->default(5.00);
            $table->enum('commission_type', ['percentage', 'fixed', 'tiered'])->default('percentage');
            $table->decimal('fixed_commission_amount', 10, 2)->nullable();

            // Segmentación opcional (guardamos UUIDs como texto por ahora)
            $table->json('allowed_categories')->nullable();   // array de UUIDs
            $table->json('excluded_products')->nullable();    // array de UUIDs

            // Métricas globales
            $table->unsignedBigInteger('total_clicks')->default(0);
            $table->unsignedBigInteger('total_conversions')->default(0);
            $table->decimal('total_sales', 12, 2)->default(0);
            $table->decimal('total_commission_earned', 12, 2)->default(0);
            $table->decimal('total_commission_paid', 12, 2)->default(0);
            $table->decimal('pending_commission', 12, 2)->default(0);
            $table->decimal('conversion_rate', 5, 2)->default(0);
            $table->decimal('average_order_value', 10, 2)->default(0);

            // Estado
            $table->enum('status', ['pending', 'active', 'suspended', 'rejected'])->default('pending');
            $table->boolean('is_active')->default(true);

            // Términos y condiciones
            $table->boolean('terms_accepted')->default(false);
            $table->timestampTz('terms_accepted_at')->nullable();
            $table->string('terms_version')->nullable();

            // Auditoría
            $table->timestampTz('joined_at')->useCurrent();
            $table->timestampTz('approved_at')->nullable();
            $table->timestampTz('suspended_at')->nullable();
            $table->timestampTz('last_sale_at')->nullable();

            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();

            $table->timestampsTz();

            // Índices
            $table->index('affiliate_code');
            $table->index('referral_code');
            $table->index('status');
            $table->index('is_active');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliates');
    }
};
