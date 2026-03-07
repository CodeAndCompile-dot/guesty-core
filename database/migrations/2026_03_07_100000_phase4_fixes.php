<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fix NOT NULL columns that the legacy controller doesn't always populate
        Schema::table('attractions', function (Blueprint $table) {
            $table->string('templete')->nullable()->default('common')->change();
        });

        Schema::table('attraction_categories', function (Blueprint $table) {
            $table->string('templete')->nullable()->default('common')->change();
        });

        Schema::table('blog_categories', function (Blueprint $table) {
            $table->string('ordering')->nullable()->default('0')->change();
            $table->string('templete')->nullable()->default('common')->change();
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->string('blog_category_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        // No-op: preserving legacy column definitions
    }
};
