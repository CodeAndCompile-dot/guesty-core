<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create the missing property_room_item_images table
        Schema::create('property_room_item_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sub_room_id');
            $table->string('sub_room_image')->nullable();
            $table->timestamps();
        });

        // Fix NOT NULL columns that have no default
        Schema::table('properties', function (Blueprint $table) {
            $table->string('instant_booking_button', 20)->nullable()->default('')->change();
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->string('templete')->nullable()->change();
        });

        // Fix date columns to be nullable for test flexibility
        Schema::table('properties_rates_group', function (Blueprint $table) {
            $table->date('start_date')->nullable()->change();
            $table->date('end_date')->nullable()->change();
            $table->bigInteger('start_date_timestamp')->nullable()->change();
            $table->bigInteger('end_date_timestamp')->nullable()->change();
            $table->string('name_of_price', 1025)->nullable()->change();
            $table->string('type_of_price', 1025)->nullable()->change();
        });

        Schema::table('property_rates', function (Blueprint $table) {
            $table->date('single_date')->nullable()->change();
            $table->bigInteger('single_date_timestamp')->nullable()->change();
            $table->decimal('price')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_room_item_images');
    }
};
