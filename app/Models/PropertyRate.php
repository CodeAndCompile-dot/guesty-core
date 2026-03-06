<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyRate extends Model
{
    use HasFactory;

    protected $table = 'property_rates';

    protected $fillable = [
        'property_id',
        'single_date',
        'single_date_timestamp',
        'price',
        'is_available',
        'platform_type',
        'currency',
        'base_price',
        'notes',
        'min_stay',
        'base_min_stay',
        'checkin_day',
        'checkout_day',
        'discount_weekly',
        'discount_monthly',
        'rate_group_id',
    ];

    protected $casts = [
        'single_date'           => 'date',
        'single_date_timestamp' => 'integer',
        'price'                 => 'decimal:2',
        'base_price'            => 'decimal:2',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function rateGroup(): BelongsTo
    {
        return $this->belongsTo(PropertyRateGroup::class, 'rate_group_id');
    }
}
