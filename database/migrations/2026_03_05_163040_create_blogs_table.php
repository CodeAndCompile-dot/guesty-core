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
        Schema::create('blogs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('seo_url');
            $table->string('image')->nullable();
            $table->string('publish')->default('published');
            $table->longText('longDescription')->nullable();
            $table->longText('shortDescription')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('featureImage')->nullable();
            $table->string('blog_category_id');
            $table->timestamps();
            $table->integer('agent_id')->nullable();
            $table->string('title_ger', 1091)->nullable();
            $table->longText('longDescription_ger')->nullable();
            $table->longText('shortDescription_ger')->nullable();
            $table->text('meta_description_ger')->nullable();
            $table->text('meta_keywords_ger')->nullable();
            $table->text('meta_title_ger')->nullable();
            $table->string('status', 100)->default('active');
            $table->integer('location_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blogs');
    }
};
