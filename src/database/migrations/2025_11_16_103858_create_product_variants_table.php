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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();

            // Foreign key to products table
            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Variant-specific fields
            $table->string('name')->nullable();
            $table->string('sku')->nullable();          // unique per variant
            $table->string('barcode')->nullable();    // optional

            // Pricing
            $table->decimal('cost', 10, 2);
            $table->decimal('sales_price', 10, 2);    // actual sell price of this variant

            // Stock
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(0);

            // Flags
            $table->boolean('is_default')->default(false); // mark default variant
            $table->boolean('visible')->default(true);

            // Variant-specific image (e.g., specific color)
            $table->string('image')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
