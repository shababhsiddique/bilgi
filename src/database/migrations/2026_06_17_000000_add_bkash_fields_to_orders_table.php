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
        Schema::table('orders', function (Blueprint $table) {
            // bKash personal-transfer details submitted by the customer at
            // checkout. Verified manually before the order is fulfilled.
            $table->string('payment_trx_id')->nullable()->after('payment_note');
            $table->string('payment_sender_number', 20)->nullable()->after('payment_trx_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_trx_id', 'payment_sender_number']);
        });
    }
};
