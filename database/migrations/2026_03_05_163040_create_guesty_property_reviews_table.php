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
        Schema::create('guesty_property_reviews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('_id')->nullable();
            $table->string('externalReviewId')->nullable();
            $table->string('accountId')->nullable();
            $table->string('channelId')->nullable();
            $table->string('createdAt')->nullable();
            $table->string('createdAtGuesty')->nullable();
            $table->string('externalListingId')->nullable();
            $table->string('externalReservationId')->nullable();
            $table->string('guestId')->nullable();
            $table->string('listingId')->nullable();
            $table->longText('rawReview')->nullable();
            $table->string('reservationId')->nullable();
            $table->string('updatedAt')->nullable();
            $table->string('updatedAtGuesty')->nullable();
            $table->longText('reviewReplies')->nullable();
            $table->longText('all_data')->nullable();
            $table->longText('guest_data')->nullable();
            $table->string('full_name')->nullable()->index('guesty_property_reviews_full_name_index');
            $table->timestamps();

            $table->index(['_id', 'guestId', 'listingId'], 'guesty_property_reviews_id_composite_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guesty_property_reviews');
    }
};
