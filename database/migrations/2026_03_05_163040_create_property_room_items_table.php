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
        Schema::create('property_room_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sub_room_title', 1025);
            $table->string('sub_room_sub_title', 1025)->nullable();
            $table->longText('sub_room_description')->nullable();
            $table->string('sub_room_status')->nullable()->default('active');
            $table->string('room_id');
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
        Schema::dropIfExists('property_room_items');
    }
};
