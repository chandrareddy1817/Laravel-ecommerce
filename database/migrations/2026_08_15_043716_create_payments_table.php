<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('provider')->default('razorpay');

            $table->string('gateway_order_id')->nullable()->unique();

            $table->string('gateway_payment_id')->nullable()->unique();

            $table->string('gateway_signature')->nullable();

            $table->decimal('amount', 10, 2);

            $table->string('currency', 3)->default('INR');

            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
