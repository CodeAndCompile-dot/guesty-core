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
        Schema::create('onboarding_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('mobile', 100)->nullable();
            $table->string('bill_to_address', 100)->nullable();
            $table->text('rental_property_address')->nullable();
            $table->string('owner_birthday')->nullable();
            $table->string('company_name', 100)->nullable();
            $table->string('social_security_number', 100)->nullable();
            $table->string('business_ein_number', 100)->nullable();
            $table->string('routing_number_of_deposites', 100)->nullable();
            $table->string('account_number', 100)->nullable();
            $table->string('account_name', 100)->nullable();
            $table->string('account_card_number', 100)->nullable();
            $table->string('account_exp', 100)->nullable();
            $table->string('account_cvv', 100)->nullable();
            $table->mediumText('housekeeping_closet_access')->nullable();
            $table->mediumText('wifi_lock_Access')->nullable();
            $table->mediumText('security_camera_login_instruction')->nullable();
            $table->string('file1')->nullable();
            $table->string('file2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('onboarding_requests');
    }
};
