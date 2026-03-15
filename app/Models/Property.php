<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    use HasFactory;

    protected $table = 'properties';

    protected $fillable = [
        'name',
        'seo_url',
        'heading',
        'price',
        'address',
        'property_status',
        'location_id',
        'mobile',
        'email',
        'website',
        'short_description',
        'long_description',
        'description',
        'cancellation_policy',
        'booking_policy',
        'notes',
        'bedroom',
        'bathroom',
        'beds',
        'sleeps',
        'area',
        'full_bath',
        'half_bath',
        'spaces',
        'feature_image',
        'banner_image',
        'cleaning_fee',
        'heating_swimming_pool_fee',
        'refundable_damage_fee',
        'pet_fee',
        'tax',
        'propane_gas',
        'checkin',
        'checkout',
        'category',
        'bed_type',
        'property_view',
        'status',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'header_section',
        'footer_section',
        'tags',
        'is_home',
        'is_trending',
        'is_top',
        'is_feature',
        'is_bestseller',
        'is_sale',
        'is_hot',
        'min_stay',
        'standard_rate',
        'checkin_day',
        'checkout_day',
        'map',
        'max_pet',
        'pet_fee_interval',
        'guest_fee',
        'no_of_guest',
        'welcome_package_description',
        'welcome_package_attachment',
        'rental_aggrement_attachment',
        'instant_booking_button',
        'api_id',
        'api_pms',
        'extra_bed',
        'queen_beds',
        'king_beds',
        'ordering',
        'vrbo_link',
        'airbnb_link',
        'heating_pool_fee',
        'heating_pool_fee_type',
        'pet_fee_type',
    ];

    protected function casts(): array
    {
        return [
            'location_id'  => 'integer',
            'bedroom'      => 'integer',
            'bathroom'     => 'integer',
            'beds'         => 'integer',
            'sleeps'       => 'integer',
            'full_bath'    => 'integer',
            'half_bath'    => 'integer',
            'cleaning_fee' => 'decimal:2',
            'heating_swimming_pool_fee' => 'decimal:2',
            'refundable_damage_fee'     => 'decimal:2',
            'tax'          => 'decimal:2',
            'propane_gas'  => 'decimal:2',
            'min_stay'     => 'integer',
            'standard_rate' => 'integer',
            'king_beds'    => 'integer',
            'queen_beds'   => 'integer',
            'ordering'     => 'integer',
            'heating_pool_fee' => 'float',
        ];
    }

    /* ------------------------------------------------------------------ */
    /*  Relationships                                                      */
    /* ------------------------------------------------------------------ */

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(PropertyGallery::class, 'property_id')->orderBy('sorting');
    }

    public function fees(): HasMany
    {
        return $this->hasMany(PropertyFee::class, 'property_id');
    }

    public function spaces(): HasMany
    {
        return $this->hasMany(PropertySpace::class, 'property_id');
    }

    public function amenityGroups(): HasMany
    {
        return $this->hasMany(PropertyAmenityGroup::class, 'property_id');
    }

    public function rateGroups(): HasMany
    {
        return $this->hasMany(PropertyRateGroup::class, 'property_id');
    }

    public function rates(): HasMany
    {
        return $this->hasMany(PropertyRate::class, 'property_id');
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(PropertyRoom::class, 'property_id');
    }
}
