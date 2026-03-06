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
        Schema::create('property_management_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('name', 1025)->nullable();
            $table->string('email', 1025)->nullable();
            $table->string('mobile', 1025)->nullable();
            $table->text('property_address')->nullable();
            $table->string('property_type')->nullable();
            $table->string('number_of_bedrooms')->nullable();
            $table->string('number_of_bathrooms')->nullable();
            $table->string('what_is_your_rental_goal')->nullable();
            $table->text('what_are_you_looking_to_rent_your_property')->nullable();
            $table->string('is_the_property_currently_closed')->nullable();
            $table->text('message')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_management_requests');
    }
};
