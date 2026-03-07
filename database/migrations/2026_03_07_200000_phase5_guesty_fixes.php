<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Phase 5 fixes for Guesty tables:
     * - Make `all_data` nullable in guesty_property_bookings (legacy bug: stores JSON, may be empty)
     * - Make `start_date` nullable in guesty_availablity_prices (sync may not always populate)
     * - Make `listingId` nullable in guesty_availablity_prices
     * - Add missing columns to guesty_properties (sub_location_id, rental fields, signature)
     * - Add index on guesty_availablity_prices for performance
     */
    public function up(): void
    {
        Schema::table('guesty_property_bookings', function (Blueprint $table) {
            $table->longText('all_data')->nullable()->change();
        });

        Schema::table('guesty_availablity_prices', function (Blueprint $table) {
            $table->date('start_date')->nullable()->change();
            $table->string('listingId')->nullable()->change();
            $table->index(['listingId', 'start_date'], 'guesty_avail_prices_listing_date_index');
        });

        Schema::table('guesty_properties', function (Blueprint $table) {
            $table->integer('sub_location_id')->nullable()->after('location_id');
            $table->string('rental_aggrement_attachment')->nullable();
            $table->string('rental_aggrement_status')->nullable();
            $table->text('signature')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('guesty_property_bookings', function (Blueprint $table) {
            $table->longText('all_data')->nullable(false)->change();
        });

        Schema::table('guesty_availablity_prices', function (Blueprint $table) {
            $table->dropIndex('guesty_avail_prices_listing_date_index');
            $table->date('start_date')->nullable(false)->change();
            $table->string('listingId')->nullable(false)->change();
        });

        Schema::table('guesty_properties', function (Blueprint $table) {
            $table->dropColumn(['sub_location_id', 'rental_aggrement_attachment', 'rental_aggrement_status', 'signature']);
        });
    }
};
