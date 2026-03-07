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
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('seo_url');
            $table->string('image')->nullable();
            $table->longText('shortDescription')->nullable();
            $table->longText('benefits')->nullable();
            $table->longText('longDescription')->nullable();
            $table->string('isHome')->default('false');
            $table->string('publish')->default('published');
            $table->string('isParent')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('bannerImage')->nullable();
            $table->string('ordering');
            $table->string('templete');
            $table->timestamps();
            $table->string('title_ger')->nullable();
            $table->longText('shortDescription_ger')->nullable();
            $table->longText('longDescription_ger')->nullable();
            $table->text('meta_title_ger')->nullable();
            $table->text('meta_keywords_ger')->nullable();
            $table->text('meta_description_ger')->nullable();
            $table->longText('benefits_ger')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_categories');
    }
};
