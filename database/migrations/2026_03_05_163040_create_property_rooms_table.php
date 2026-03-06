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
        Schema::create('property_rooms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('room_title', 1025);
            $table->string('room_sub_title', 1025)->nullable();
            $table->longText('room_description')->nullable();
            $table->string('room_status')->nullable()->default('active');
            $table->string('property_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_rooms');
    }
};
