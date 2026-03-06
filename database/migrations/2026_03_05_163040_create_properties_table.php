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
        Schema::create('properties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('seo_url');
            $table->string('heading')->nullable();
            $table->string('price')->nullable()->default('0');
            $table->text('address')->nullable();
            $table->string('mobile', 50)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('website', 191)->nullable();
            $table->longText('short_description')->nullable();
            $table->longText('long_description')->nullable();
            $table->longText('description')->nullable();
            $table->longText('cancellation_policy')->nullable();
            $table->longText('booking_policy')->nullable();
            $table->longText('notes')->nullable();
            $table->bigInteger('bedroom')->nullable()->default(0);
            $table->bigInteger('bathroom')->nullable();
            $table->bigInteger('beds')->nullable();
            $table->bigInteger('sleeps')->nullable();
            $table->string('area')->nullable();
            $table->bigInteger('full_bath')->nullable();
            $table->bigInteger('half_bath')->nullable();
            $table->string('spaces')->nullable();
            $table->string('feature_image')->nullable();
            $table->decimal('cleaning_fee')->nullable();
            $table->decimal('heating_swimming_pool_fee')->nullable();
            $table->decimal('refundable_damage_fee')->nullable();
            $table->decimal('tax')->nullable();
            $table->decimal('propane_gas')->nullable();
            $table->string('status', 10)->default('true');
            $table->longText('meta_title')->nullable();
            $table->longText('meta_keywords')->nullable();
            $table->longText('meta_description')->nullable();
            $table->longText('header_section')->nullable();
            $table->longText('footer_section')->nullable();
            $table->timestamps();
            $table->longText('tags')->nullable();
            $table->string('is_home', 10)->nullable()->default('false');
            $table->string('is_trending', 10)->nullable()->default('false');
            $table->string('is_top', 10)->nullable()->default('false');
            $table->string('is_feature', 10)->nullable()->default('false');
            $table->string('is_bestseller', 10)->nullable()->default('false');
            $table->string('is_sale', 10)->nullable()->default('false');
            $table->string('is_hot', 10)->nullable()->default('false');
            $table->string('banner_image')->nullable();
            $table->integer('location_id')->nullable();
            $table->string('property_status', 50)->nullable();
            $table->integer('standard_rate')->nullable();
            $table->integer('min_stay')->nullable();
            $table->mediumText('map')->nullable();
            $table->string('checkin', 50)->nullable();
            $table->string('checkout', 50)->nullable();
            $table->string('category', 50)->nullable();
            $table->string('bed_type', 50)->nullable();
            $table->string('property_view', 50)->nullable();
            $table->text('rental_aggrement_attachment')->nullable();
            $table->longText('welcome_package_description')->nullable();
            $table->text('welcome_package_attachment')->nullable();
            $table->string('instant_booking_button', 20);
            $table->text('api_id')->nullable();
            $table->text('api_pms')->nullable();
            $table->integer('king_beds')->nullable()->default(0);
            $table->integer('queen_beds')->nullable()->default(0);
            $table->string('extra_bed', 1025)->nullable();
            $table->integer('ordering')->default(0);
            $table->text('vrbo_link')->nullable();
            $table->text('airbnb_link')->nullable();
            $table->string('checkin_day')->nullable();
            $table->string('checkout_day')->nullable();
            $table->string('pet_fee', 50)->nullable();
            $table->string('pet_fee_interval', 50)->nullable();
            $table->string('max_pet', 50)->nullable();
            $table->string('guest_fee', 50)->nullable();
            $table->string('no_of_guest', 50)->nullable();
            $table->double('heating_pool_fee')->nullable();
            $table->string('pet_fee_type', 191)->default('taxable');
            $table->string('heating_pool_fee_type', 191)->default('taxable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properties');
    }
};
