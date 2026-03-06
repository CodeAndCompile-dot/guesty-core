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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('message')->nullable();
            $table->string('image')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('profile')->nullable();
            $table->string('stay_date')->nullable();
            $table->string('score')->nullable();
            $table->string('property_id')->nullable();
            $table->timestamps();
            $table->string('status')->default('false');
            $table->integer('ordering')->nullable()->default(0);

            $table->index(['name', 'property_id', 'status'], 'name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('testimonials');
    }
};
