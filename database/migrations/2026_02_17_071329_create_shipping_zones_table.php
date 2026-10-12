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
        Schema::create('shipping_zones', function (Blueprint $table) {
            $table->id();
            $table->string('country')->default('Myanmar');
            $table->string('name'); // Yangon, Mandalay, Bago, etc
            $table->decimal('fee_mmk', 12, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['country','name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_zones');
    }
};
