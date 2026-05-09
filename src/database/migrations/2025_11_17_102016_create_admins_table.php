<?php

use App\Models\Admin;
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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role', 10)->default('admin')->comment('admin, staff, customer, etc.');
            $table->rememberToken();
            $table->timestamps();
        });

        // 1 user
        $user = Admin::factory()->create([
            'name' => 'Administrator',
            'email' => 'shabab.h.siddique@gmail.com',
            'password' => bcrypt('1234'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
