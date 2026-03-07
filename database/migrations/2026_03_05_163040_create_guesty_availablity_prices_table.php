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
        Schema::create('guesty_availablity_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('start_date');
            $table->string('listingId');
            $table->double('price')->nullable();
            $table->integer('minNights')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('guesty_availablity_prices');
    }
};
