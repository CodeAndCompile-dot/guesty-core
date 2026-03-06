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
        Schema::create('our_teams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('profile')->nullable();
            $table->timestamps();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('bannerImage')->nullable();
            $table->string('seo_url');
            $table->longText('longDescription')->nullable();
            $table->longText('header_section')->nullable();
            $table->longText('footer_section')->nullable();
            $table->longText('meta_title')->nullable();
            $table->longText('meta_keywords')->nullable();
            $table->longText('meta_description')->nullable();
            $table->string('contactImage')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('our_teams');
    }
};
