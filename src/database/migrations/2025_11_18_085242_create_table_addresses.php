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
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();

            // Assuming customer_id references users or customers table, adjust as needed
            $table->unsignedBigInteger('customer_id');

            $table->boolean('is_default')->default(false);

            $table->string('address_name',30);

            $table->string('name');
            $table->string('phone', 30)->nullable();
            $table->text('address');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postcode', 20)->nullable();
            $table->string('country')->default('Bangladesh');

            $table->softDeletes(); // soft delete column: deleted_at
            $table->timestamps();

            // Add FK if you have a customers table:
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
