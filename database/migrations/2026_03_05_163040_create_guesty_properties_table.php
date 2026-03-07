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
        Schema::create('guesty_properties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('_id')->nullable();
            $table->longText('picture')->nullable();
            $table->text('terms')->nullable();
            $table->string('terms_min_night')->nullable();
            $table->string('terms_max_night')->nullable();
            $table->longText('prices')->nullable();
            $table->longText('publicDescription')->nullable();
            $table->longText('summary')->nullable();
            $table->longText('space')->nullable();
            $table->longText('access')->nullable();
            $table->longText('interactionWithGuests')->nullable();
            $table->longText('neighborhood')->nullable();
            $table->longText('transit')->nullable();
            $table->longText('notes')->nullable();
            $table->longText('houseRules')->nullable();
            $table->longText('privateDescription')->nullable();
            $table->string('type')->nullable();
            $table->longText('amenities')->nullable();
            $table->longText('amenitiesNotIncluded')->nullable();
            $table->string('active')->nullable();
            $table->string('nickname')->nullable();
            $table->string('title')->nullable();
            $table->string('propertyType')->nullable();
            $table->string('roomType')->nullable();
            $table->string('bedrooms')->nullable();
            $table->string('bathrooms')->nullable();
            $table->string('beds')->nullable();
            $table->string('isListed')->nullable();
            $table->longText('address')->nullable();
            $table->string('defaultCheckInTime')->nullable();
            $table->string('defaultCheckInEndTime')->nullable();
            $table->string('defaultCheckOutTime')->nullable();
            $table->string('accommodates')->nullable();
            $table->longText('pictures')->nullable();
            $table->string('accountId')->nullable();
            $table->string('createdAt')->nullable();
            $table->string('lastUpdatedAt')->nullable();
            $table->longText('all_data')->nullable();
            $table->text('seo_url')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('banner_image')->nullable();
            $table->string('is_home')->nullable()->default('false');
            $table->string('status')->nullable()->default('true');
            $table->timestamps();
            $table->integer('guests')->nullable()->default(0);
            $table->integer('location_id')->nullable();
            $table->longText('map')->nullable();
            $table->integer('ordering')->nullable()->default(0);
            $table->string('booklet')->nullable();
            $table->string('feature_image')->nullable();
            $table->string('ogimage')->nullable();
            $table->text('cancellation_policy')->nullable();

            $table->index(['_id', 'bedrooms', 'bathrooms'], 'guesty_properties_id_composite_index');
            $table->index(['beds', 'is_home', 'status', 'guests'], 'guesty_properties_beds_composite_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guesty_properties');
    }
};
