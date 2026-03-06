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
        Schema::create('cms', function (Blueprint $table) {
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
            $table->longText('seo_section')->nullable();
            $table->string('image_2')->nullable();
            $table->string('image_3')->nullable();
            $table->string('faq_title')->nullable();
            $table->text('faq_desction')->nullable();
            $table->string('faq_image')->nullable();
            $table->string('strip_title')->nullable();
            $table->text('strip_desction')->nullable();
            $table->string('strip_image')->nullable();
            $table->string('about_image1')->nullable();
            $table->string('about_image2')->nullable();
            $table->string('owner_image')->nullable();
            $table->text('strip_anchor')->nullable();
            $table->string('ogimage')->nullable();
            $table->string('section_image')->nullable();
            $table->text('section_desc')->nullable();
            $table->text('section2_desc')->nullable();
            $table->text('section3_desc')->nullable();
            $table->text('section4_desc')->nullable();
            $table->text('section4_sub_heading1')->nullable();
            $table->text('section4_sub_desc1')->nullable();
            $table->text('section4_sub_heading2')->nullable();
            $table->text('section4_sub_desc2')->nullable();
            $table->text('section4_sub_heading3')->nullable();
            $table->text('section4_sub_desc3')->nullable();
            $table->text('section5_desc')->nullable();
            $table->text('section5_sub_heading1')->nullable();
            $table->text('section5_sub_desc1')->nullable();
            $table->text('section5_sub_heading2')->nullable();
            $table->text('section5_sub_desc2')->nullable();
            $table->text('section5_sub_heading3')->nullable();
            $table->text('section5_sub_desc3')->nullable();
            $table->text('section6_desc')->nullable();

            $table->index(['name', 'seo_url', 'templete'], 'cms_name_seo_url_templete_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cms');
    }
};
