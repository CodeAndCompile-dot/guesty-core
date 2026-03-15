<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PropertyRateGroup extends Model
{
    use HasFactory;

    protected $table = 'properties_rates_group';

    protected $fillable = [
        'property_id',
        'name_of_price',
        'type_of_price',
        'start_date',
        'start_date_timestamp',
        'end_date',
        'end_date_timestamp',
        'price',
        'monday_price',
        'tuesday_price',
        'wednesday_price',
        'thrusday_price',
        'friday_price',
        'saturday_price',
        'sunday_price',
        'discount_weekly',
        'discount_monthly',
        'checkin_day',
        'checkout_day',
        'is_available',
        'platform_type',
        'currency',
        'base_price',
        'notes',
        'min_stay',
        'base_min_stay',
    ];

    protected function casts(): array
    {
        return [
            'start_date'           => 'date',
            'end_date'             => 'date',
            'start_date_timestamp' => 'integer',
            'end_date_timestamp'   => 'integer',
            'price'                => 'decimal:2',
            'base_price'           => 'decimal:2',
            'monday_price'         => 'float',
            'tuesday_price'        => 'float',
            'wednesday_price'      => 'float',
            'thrusday_price'       => 'float',
            'friday_price'         => 'float',
            'saturday_price'       => 'float',
            'sunday_price'         => 'float',
        ];
    }

    /* ------------------------------------------------------------------ */
    /*  Relationships                                                      */
    /* ------------------------------------------------------------------ */

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function rates(): HasMany
    {
        return $this->hasMany(PropertyRate::class, 'rate_group_id');
    }
}
