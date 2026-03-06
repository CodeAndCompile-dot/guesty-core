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
        Schema::create('property_spaces', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('property_id');
            $table->timestamps();
            $table->text('space_name');
            $table->string('space_image', 1025)->nullable();
            $table->string('space_status')->default('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_spaces');
    }
};
