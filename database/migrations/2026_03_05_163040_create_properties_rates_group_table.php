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
        Schema::create('properties_rates_group', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('property_id');
            $table->date('start_date');
            $table->bigInteger('start_date_timestamp');
            $table->date('end_date');
            $table->bigInteger('end_date_timestamp');
            $table->decimal('price')->nullable();
            $table->string('is_available')->default('1');
            $table->string('platform_type')->default('airbnb');
            $table->string('currency')->default('USD');
            $table->decimal('base_price')->nullable();
            $table->text('notes')->nullable();
            $table->string('min_stay')->nullable();
            $table->string('base_min_stay')->nullable();
            $table->timestamps();
            $table->string('discount_weekly', 1025)->nullable();
            $table->string('discount_monthly', 1025)->nullable();
            $table->string('name_of_price', 1025);
            $table->string('type_of_price', 1025);
            $table->double('monday_price')->nullable();
            $table->double('tuesday_price')->nullable();
            $table->double('wednesday_price')->nullable();
            $table->double('thrusday_price')->nullable();
            $table->double('friday_price')->nullable();
            $table->double('saturday_price')->nullable();
            $table->double('sunday_price')->nullable();
            $table->string('checkin_day')->nullable();
            $table->string('checkout_day')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properties_rates_group');
    }
};
