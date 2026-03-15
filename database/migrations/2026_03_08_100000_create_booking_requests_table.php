<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('booking_requests')) {
            return;
        }

        Schema::create('booking_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('property_id');
            $table->date('checkin');
            $table->date('checkout');
            $table->integer('total_guests')->nullable();
            $table->integer('adults')->nullable();
            $table->integer('child')->nullable();
            $table->double('gross_amount')->nullable();
            $table->double('total_night')->nullable();
            $table->double('sub_amount')->nullable();
            $table->double('total_amount')->nullable();
            $table->longText('after_total_fees')->nullable();
            $table->longText('before_total_fees')->nullable();
            $table->string('request_id')->nullable();
            $table->string('booking_status')->nullable()->default('booked');
            $table->string('email_status')->nullable()->default('false');
            $table->string('payment_status')->nullable()->default('pending');
            $table->string('welcome_email')->nullable()->default('false');
            $table->string('review_email')->nullable()->default('false');
            $table->string('reminder_email')->nullable()->default('false');
            $table->string('third_reminder_email')->nullable()->default('false');
            $table->string('checkin_email')->nullable()->default('false');
            $table->string('checkout_email')->nullable()->default('false');
            $table->string('firstname', 50)->nullable();
            $table->string('lastname', 50)->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('mobile')->nullable();
            $table->longText('message')->nullable();
            $table->string('ip_address')->nullable();
            $table->longText('cancel_reason')->nullable();
            $table->longText('note')->nullable();
            $table->timestamps();
            $table->string('rental_aggrement_status')->default('false');
            $table->text('rental_aggrement_signature')->nullable();
            $table->longText('rental_aggrement_images')->nullable();
            $table->text('rental_agreement_link')->nullable();
            $table->integer('total_payment')->nullable()->default(1);
            $table->mediumText('amount_data')->nullable();
            $table->integer('how_many_payment_done')->nullable()->default(0);
            $table->string('total_pets', 50)->nullable();
            $table->string('pet_fee', 50)->nullable();
            $table->string('guest_fee', 50)->nullable();
            $table->string('rest_guests', 50)->nullable();
            $table->string('single_guest_fee', 50)->nullable();
            $table->string('discount', 50)->nullable();
            $table->string('discount_coupon', 50)->nullable();
            $table->string('after_discount_total', 50)->nullable();
            $table->integer('extra_discount')->nullable()->default(0);
            $table->string('color', 50)->nullable()->default('red');
            $table->double('heating_pool_fee')->nullable();
            $table->string('tax', 155)->nullable();
            $table->string('define_tax', 155)->nullable();
            $table->string('pet_fee_type', 191)->nullable()->default('taxable');
            $table->string('heating_pool_fee_type', 191)->nullable()->default('taxable');
            $table->string('booking_type_admin')->nullable()->default('invoice');
            $table->string('booking_guesty_id', 1025)->nullable();
            $table->longText('booking_guesty_json')->nullable();
            $table->text('rate_api_id')->nullable();
            $table->text('stripe_intent_data_id')->nullable();
            $table->text('stripe_main_payment_method')->nullable();
            $table->text('quote_id')->nullable();
            $table->string('card_number', 50)->nullable();
            $table->string('card_cvv', 50)->nullable();
            $table->string('card_expiry_month', 50)->nullable();
            $table->string('card_expiry_year', 50)->nullable();
            $table->text('address_line_1')->nullable();
            $table->string('city')->nullable();
            $table->string('zipcode', 50)->nullable();
            $table->string('country', 50)->nullable();
            $table->text('new_guest_id')->nullable();
            $table->longText('new_guest_object')->nullable();
            $table->longText('new_pre_booking_object')->nullable();
            $table->longText('new_result_booking_object')->nullable();
            $table->text('new_property_id')->nullable();
            $table->string('new_booking_status')->nullable();
            $table->text('new_reservation_id')->nullable();
            $table->text('payment_object')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_requests');
    }
};
