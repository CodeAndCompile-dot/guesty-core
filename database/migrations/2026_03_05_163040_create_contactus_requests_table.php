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
        Schema::create('contactus_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('name', 1025)->nullable();
            $table->string('email', 1025)->nullable();
            $table->string('mobile', 1025)->nullable();
            $table->longText('message')->nullable();
            $table->string('date_of_request', 1025)->nullable();
            $table->string('budget', 1025)->nullable();
            $table->string('guests', 1025)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contactus_requests');
    }
};
