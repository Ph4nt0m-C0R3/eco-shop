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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('currency_code', 10)->default('USD');

            $table->decimal('subtotal_mmk', 12, 2);
            $table->decimal('subtotal_usd', 12, 2);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('shipping_fee', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2);

            $table->enum('status', [
                'pending_payment',   // waiting for payment
                'processing',        // payment verified, preparing
                'shipped',           // shipped
                'delivered',         // delivered
                'cancelled'          // cancelled
            ])->default('pending_payment');

            $table->string('delivery_type')->default('delivery');

            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods');
            $table->string('payment_method');
            $table->string('payment_reference')->nullable();
            $table->string('payment_screenshot')->nullable();
            $table->string('payment_status')->default('unpaid');

            $table->string('stripe_session_id')->nullable();

            $table->string('phone')->nullable();
            $table->text('shipping_address')->nullable();

            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
