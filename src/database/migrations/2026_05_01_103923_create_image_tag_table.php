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
        Schema::create('image_tag', function (Blueprint $table) {
            $table->unsignedBigInteger('image_uniqid');
            $table->unsignedBigInteger('tag_uniqid');

            $table->foreign('image_uniqid')->references('uniqid')->on('images')->cascadeOnDelete();
            $table->foreign('tag_uniqid')->references('uniqid')->on('tags')->cascadeOnDelete();

            $table->unique(['image_uniqid', 'tag_uniqid']);
            $table->index(['tag_uniqid', 'image_uniqid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image_tag');
    }
};
