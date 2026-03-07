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
        Schema::create('guesty_property_bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('_id')->nullable();
            $table->string('integration')->nullable();
            $table->string('confirmationCode')->nullable();
            $table->string('checkIn')->nullable();
            $table->string('checkOut')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('listingId')->nullable();
            $table->string('guest')->nullable();
            $table->string('accountId')->nullable();
            $table->string('guestId')->nullable();
            $table->string('listing')->nullable();
            $table->longText('all_data');
            $table->timestamps();

            $table->index(['_id', 'start_date', 'end_date', 'listingId'], 'guesty_property_bookings_id_composite_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guesty_property_bookings');
    }
};
