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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Multilingual
            $table->string('name_en');
            $table->string('name_mm');

            $table->longText('description_en')->nullable();
            $table->longText('description_mm')->nullable();

            // Pricing
            $table->decimal('price_usd', 10, 2);

            // Relations
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();

            // Inventory
            $table->unsignedInteger('stock')->default(0);

            // Eco attributes
            $table->string('eco_badge')->nullable(); // recyclable, organic, etc
            $table->string('eco_badge_mm')->nullable();

            // Status
            $table->boolean('is_active')->default(true);

            // Audit
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
