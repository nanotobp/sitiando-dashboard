<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_activity_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('cart_id');

            $table->string('event'); 
            // add_item / remove_item / update_qty / viewed_cart / checkout_start / abandoned / restored

            $table->json('payload')->nullable(); // datos extra para auditoría

            $table->timestamps();

            $table->foreign('cart_id')->references('id')->on('carts')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_activity_logs');
    }
};
