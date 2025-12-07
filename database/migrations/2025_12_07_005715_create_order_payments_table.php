<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('order_id');
            $table->string('status')->default('pending');
            $table->decimal('amount', 12, 2)->default(0);

            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->json('gateway_response')->nullable();

            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')->on('orders')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_payments');
    }
};
