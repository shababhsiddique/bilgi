<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->string('content_key')->unique();
            $table->text('content_data');
            $table->foreignId('product_id')
                ->nullable()
                ->constrained('products')
                ->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert initial data
        /*DB::table('contents')->insert([
            'content_key' => 'shop_banner_data',
            'content_data' => json_encode([
                'subtitle' => 'New Collection',
                'title' => 'Kids Toy',
                'product_id' => 1,
                'text' => 'Flat <span class="font-semibold text-rose-500">20% Off</span> on magnetic construction toys for your kids.'
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);*/

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
