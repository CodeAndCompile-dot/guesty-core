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
        Schema::create('guesty_property_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('property_id')->index('guesty_property_prices_property_id_index');
            $table->longText('prices')->nullable();
            $table->string('monthlyPriceFactor')->nullable();
            $table->string('weeklyPriceFactor')->nullable();
            $table->string('currency')->nullable();
            $table->string('basePrice')->nullable();
            $table->string('weekendBasePrice')->nullable();
            $table->text('weekendDays')->nullable();
            $table->string('securityDepositFee')->nullable();
            $table->string('guestsIncludedInRegularFee')->nullable();
            $table->string('extraPersonFee')->nullable();
            $table->string('cleaningFee')->nullable();
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
        Schema::dropIfExists('guesty_property_prices');
    }
};
