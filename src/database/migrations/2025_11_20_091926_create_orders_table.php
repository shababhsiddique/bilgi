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

            // Customer relation
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            // Order core
            $table->string('order_number')->unique();

            $table->enum('status', [
                'unverified',
                'pending',
                'processing',
                'on_hold',
                'completed',
                'cancelled',
                'refunded',
                'failed',
            ])->default('pending');

            // Amounts
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('shipping_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);

            // Payment
            $table->foreignId('payment_method_id')->nullable()->constrained();

            $table->enum('payment_status', [
                'unpaid',
                'pending',
                'paid',
                'refunded',
                'failed',
            ])->default('unpaid');
            $table->text('payment_note')->nullable();

            // Shipping info
            // Shipping Address relation
            $table->foreignId('shipping_address_id')
                ->constrained('customer_addresses', 'id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            // Billing Address relation
            $table->foreignId('billing_address_id')
                ->constrained('customer_addresses', 'id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            /*$table->string('shipping_name')->nullable();
            $table->string('shipping_phone', 50)->nullable();
            $table->string('shipping_email')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_postcode', 50)->nullable();
            $table->string('shipping_country', 100)->nullable();*/

            // Notes
            $table->text('notes')->nullable();
            $table->text('seller_note')->nullable();

            // Timestamps
            $table->timestamp('placed_at')->nullable();
            $table->timestamps();

            $table->softDeletes();

            // Indexes
            $table->index('customer_id');
            $table->index('order_number');
            $table->index('status');
            $table->index('payment_status');
            $table->index('placed_at');
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
