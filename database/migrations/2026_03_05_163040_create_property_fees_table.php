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
        Schema::create('property_fees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fee_name');
            $table->string('fee_rate');
            $table->string('fee_type')->default('Excat');
            $table->string('fee_apply')->default('total');
            $table->string('fee_status')->default('active');
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
        Schema::dropIfExists('property_fees');
    }
};
