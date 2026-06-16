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
            // Steadfast "tracking_code" — the public token used to track the
            // parcel on Steadfast's customer-facing tracking page. Nullable
            // until the order is handed over for delivery.
            $table->string('tracking_token')->nullable()->after('consignment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('tracking_token');
        });
    }
};
