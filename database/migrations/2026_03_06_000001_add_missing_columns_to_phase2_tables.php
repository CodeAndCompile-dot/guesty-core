<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->string('status', 100)->default('active')->after('description');
        });

        Schema::table('cms', function (Blueprint $table) {
            $table->string('section2_img')->nullable()->after('section2_desc');
            $table->string('section4_main_img')->nullable()->after('section4_desc');
            $table->string('section4_sub_icon1')->nullable()->after('section4_sub_desc1');
            $table->string('section4_sub_icon2')->nullable()->after('section4_sub_desc2');
            $table->string('section4_sub_icon3')->nullable()->after('section4_sub_desc3');
            $table->string('section5_main_img')->nullable()->after('section5_desc');
            $table->string('section5_sub_icon1')->nullable()->after('section5_sub_desc1');
            $table->string('section5_sub_icon2')->nullable()->after('section5_sub_desc2');
            $table->string('section5_sub_icon3')->nullable()->after('section5_sub_desc3');
            $table->string('section6_img1')->nullable()->after('section6_desc');
            $table->string('section6_img2')->nullable()->after('section6_img1');
            $table->string('section6_img3')->nullable()->after('section6_img2');
        });
    }

    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('cms', function (Blueprint $table) {
            $table->dropColumn([
                'section2_img', 'section4_main_img',
                'section4_sub_icon1', 'section4_sub_icon2', 'section4_sub_icon3',
                'section5_main_img',
                'section5_sub_icon1', 'section5_sub_icon2', 'section5_sub_icon3',
                'section6_img1', 'section6_img2', 'section6_img3',
            ]);
        });
    }
};
