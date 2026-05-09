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

            // Core product type
            $table->enum('product_type', ['goods', 'service'])->default('goods');
            $table->string('age_group')->default(4);

            // Inventory & pricing
            $table->boolean('track_inventory')->default(false);
            $table->decimal('sales_tax_percent', 5, 2)->unsigned()->nullable(); // e.g. 0–100.00

            // Basic info
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('long_description')->nullable();
            $table->text('notes')->nullable();

            // Visibility & merchandising
            $table->boolean('visible')->default(true);
            $table->string('ribbon_text')->nullable(); // e.g. "New", "Best Seller"

            // Media
            $table->string('thumbnail')->nullable(); // URL or path

            // Shipping-related
            /*$table->decimal('weight', 10, 3)->nullable(); // kg or lb
            $table->decimal('volume', 10, 2)->nullable(); // e.g. cubic meters/liters*/


            // Digital/product flags
            $table->boolean('is_digital')->default(false);

            // SEO / ordering
            $table->string('slug')->unique();
            $table->string('brand')->nullable();
            $table->unsignedInteger('sort_order')->default(0);   // manual ordering

            // SEO/meta
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 512)->nullable();
            $table->string('meta_keywords', 512)->nullable(); // comma separated

            // Soft deletes in case you want to restore products
            $table->softDeletes();

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
