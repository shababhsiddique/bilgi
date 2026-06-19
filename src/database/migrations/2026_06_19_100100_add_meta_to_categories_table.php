<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Optional SEO overrides for the category landing page. When empty
            // the page falls back to the category name / description.
            $table->string('meta_title')->nullable()->after('description');
            $table->string('meta_description', 512)->nullable()->after('meta_title');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description']);
        });
    }
};
