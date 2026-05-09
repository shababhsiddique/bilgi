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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained('products')
                ->restrictOnDelete();

            $table->foreignId('product_variant_id')
                ->constrained('product_variants')
                ->restrictOnDelete();

            $table->string('product_name'); //at the time
            $table->string('variant_name')->nullable(); // at the time e.g., "Red / XL"
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();

            $table->unsignedInteger('quantity');

            // store prices as integer cents to avoid float issues
            $table->decimal('unit_price',10, 2); // price at time of order
            $table->decimal('line_total',12,2); // unit_price * quantity

            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
