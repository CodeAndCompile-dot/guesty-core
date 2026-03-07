<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GuestyProperty extends Model
{
    protected $table = 'guesty_properties';

    protected $fillable = [
        '_id',
        'picture',
        'terms',
        'terms_min_night',
        'terms_max_night',
        'prices',
        'publicDescription',
        'summary',
        'space',
        'access',
        'interactionWithGuests',
        'neighborhood',
        'transit',
        'notes',
        'houseRules',
        'privateDescription',
        'type',
        'amenities',
        'amenitiesNotIncluded',
        'active',
        'nickname',
        'title',
        'propertyType',
        'roomType',
        'bedrooms',
        'bathrooms',
        'beds',
        'isListed',
        'address',
        'defaultCheckInTime',
        'defaultCheckInEndTime',
        'defaultCheckOutTime',
        'accommodates',
        'pictures',
        'accountId',
        'createdAt',
        'lastUpdatedAt',
        'all_data',
        'guests',
        'seo_url',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'banner_image',
        'is_home',
        'status',
        'location_id',
        'sub_location_id',
        'map',
        'ordering',
        'booklet',
        'feature_image',
        'ogimage',
        'rental_aggrement_attachment',
        'rental_aggrement_status',
        'signature',
        'cancellation_policy',
    ];

    protected $casts = [
        'guests'   => 'integer',
        'ordering' => 'integer',
    ];

    /* ------------------------------------------------------------------ */
    /*  Relationships                                                      */
    /* ------------------------------------------------------------------ */

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function subLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'sub_location_id');
    }

    public function priceInfo(): HasOne
    {
        return $this->hasOne(GuestyPropertyPrice::class, 'property_id', '_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(GuestyPropertyBooking::class, 'listingId', '_id');
    }

    public function availabilityPrices(): HasMany
    {
        return $this->hasMany(GuestyAvailabilityPrice::class, 'listingId', '_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(GuestyPropertyReview::class, 'listingId', '_id');
    }
}
