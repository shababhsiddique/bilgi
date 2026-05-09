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
        Schema::create('recipee_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recipee_id');
            $table->string('material_key');
            $table->unsignedInteger('quantity');
            $table->timestamps();

            $table->foreign('recipee_id')
                ->references('id')
                ->on('recipees')
                ->onDelete('cascade');

            $table->foreign('material_key')
                ->references('material_key')
                ->on('materials')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipee_items', function (Blueprint $table) {
            $table->dropForeign(['recipee_id']);
            $table->dropForeign(['material_key']);
        });

        Schema::dropIfExists('recipee_items');
    }
};
