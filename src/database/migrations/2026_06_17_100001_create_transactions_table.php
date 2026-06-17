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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // Public-facing unique identifier
            $table->uuid('uuid')->unique();

            // Free-text references (no order integration yet)
            $table->string('transaction_id')->nullable();
            $table->string('invoice')->nullable();

            // income = money in (Cash In), expense = money out (Cash Out)
            $table->enum('type', ['income', 'expense']);

            // Category (creatable on the fly)
            $table->foreignId('category_id')
                ->nullable()
                ->constrained('transaction_categories')
                ->nullOnDelete();

            // Which wallet the money moved through
            $table->enum('wallet', ['cash', 'bkash', 'nagad'])->default('cash');

            $table->decimal('amount', 15, 2);

            $table->text('notes')->nullable();

            // When the money actually moved (drives cards + filtering)
            $table->dateTime('transaction_date');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('type');
            $table->index('wallet');
            $table->index('transaction_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
