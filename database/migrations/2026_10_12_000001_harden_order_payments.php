<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('exchange_rate_mmk', 18, 8)->nullable();
            $table->timestamp('reservation_expires_at')->nullable()->index();
            $table->timestamp('reservation_released_at')->nullable();
            $table->timestamp('inventory_committed_at')->nullable();
        });
        \Illuminate\Support\Facades\DB::table('orders')->where('payment_method', 'stripe')
            ->where('status', 'pending_payment')->where('payment_status', '!=', 'paid')
            ->orderBy('id')->chunkById(100, function ($orders) {
                foreach ($orders as $order) {
                    \Illuminate\Support\Facades\DB::table('orders')->where('id', $order->id)->update([
                        'reservation_expires_at' => \Carbon\Carbon::parse($order->created_at)->addMinutes(30),
                    ]);
                }
            });
        Schema::create('payment_events', function (Blueprint $table) {
            $table->id();
            $table->string('provider_event_id')->unique();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_events');
        Schema::table('orders', fn (Blueprint $table) => $table->dropColumn([
            'exchange_rate_mmk', 'reservation_expires_at', 'reservation_released_at', 'inventory_committed_at',
        ]));
    }
};
