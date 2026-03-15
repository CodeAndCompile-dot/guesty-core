<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'image')) {
                $table->string('image')->nullable()->after('remember_token');
            }
            if (! Schema::hasColumn('users', 'bannerImage')) {
                $table->string('bannerImage')->nullable()->after('image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['image', 'bannerImage']);
        });
    }
};
