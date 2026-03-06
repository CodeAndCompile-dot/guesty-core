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
        Schema::create('locations', function (Blueprint $table) {
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
            $table->string('templete');
            $table->string('bannerImage')->nullable();
            $table->string('publish')->default('published');
            $table->longText('footer_section')->nullable();
            $table->longText('header_section')->nullable();
            $table->timestamps();
            $table->string('attraction_image')->nullable();
            $table->string('status')->default('true');
            $table->integer('ordering')->nullable()->default(0);
            $table->integer('is_parent')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
};
