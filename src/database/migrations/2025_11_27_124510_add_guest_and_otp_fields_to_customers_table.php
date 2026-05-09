<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $otpLength = (int) config('auth.one_time_password_length', 6);

        Schema::table('customers', function (Blueprint $table) use ($otpLength) {
            $table->boolean('is_guest_registered')->default(false);
            $table->string('otp_code', $otpLength)->nullable();
            $table->timestamp('otp_expires_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['is_guest_registered', 'otp_code', 'otp_expires_at']);
        });
    }
};
