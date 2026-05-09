<?php

use App\Models\PaymentMethod;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();

            // Name shown to customer — e.g. "bKash", "Card Payment", "Cash on Delivery"
            $table->string('name');

            // Unique code for internal references — e.g. bkash, cod, stripe
            $table->string('code')->unique();

            // Type (optional) — e.g. mobile_wallet, card, cash, bank_transfer
            $table->string('type')->nullable();

            // Optional description or instructions
            $table->text('description')->nullable();

            // Icon or logo path
            $table->string('icon')->nullable();

            // Position in checkout ordering
            $table->integer('sort_order')->default(0);

            // Whether customers can see & use it
            $table->boolean('is_active')->default(true);

            // Store API config (keys, phone numbers, merchant IDs, etc.)
            $table->text('config')->nullable();

            $table->decimal('payment_charge', 10, 2)
                ->default(0);

            $table->enum('payment_charge_type', ['flat', 'percent'])
                ->default('flat');


            $table->timestamps();
            $table->softDeletes();
        });


        // Cash on Delivery
        PaymentMethod::create([
            'name'        => 'Cash on Delivery',
            'code'        => 'cod',
            'type'        => 'cash',
            'description' => 'Pay with cash upon delivery.',
            'icon'        => null,
            'sort_order'  => 1,
            'is_active'   => true,
            'config'      => null,
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
