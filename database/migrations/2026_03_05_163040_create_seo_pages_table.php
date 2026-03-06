<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seo_pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('seo_url');
            $table->longText('shortDescription')->nullable();
            $table->longText('mediumDescription')->nullable();
            $table->longText('longDescription')->nullable();
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('templete')->nullable();
            $table->string('bannerImage')->nullable();
            $table->string('publish')->default('published');
            $table->longText('footer_section')->nullable();
            $table->longText('header_section')->nullable();
            $table->timestamps();
            $table->string('vacation_one_image')->nullable();
            $table->text('vacation_one_link')->nullable();
            $table->text('vacation_one_title')->nullable();
            $table->string('vacation_two_image')->nullable();
            $table->text('vacation_two_link')->nullable();
            $table->text('vacation_two_title')->nullable();
            $table->string('vacation_three_image')->nullable();
            $table->text('vacation_three_link')->nullable();
            $table->text('vacation_three_title')->nullable();
            $table->string('vacation_four_image')->nullable();
            $table->text('vacation_four_link')->nullable();
            $table->text('vacation_four_title')->nullable();
            $table->text('banner_sub_heading')->nullable();
            $table->text('banner_heading')->nullable();
            $table->text('vacation_heading')->nullable();
            $table->text('vacation_sub_heading')->nullable();
            $table->longText('attraction_secion')->nullable();
            $table->longText('video_section')->nullable();
            $table->text('vacation_one_alt')->nullable();
            $table->text('vacation_two_alt')->nullable();
            $table->text('vacation_three_alt')->nullable();
            $table->text('vacation_four_alt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seo_pages');
    }
};
