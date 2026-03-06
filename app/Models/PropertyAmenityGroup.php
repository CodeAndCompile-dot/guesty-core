<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PropertyAmenityGroup extends Model
{
    use HasFactory;

    protected $table = 'property_amenity_groups';

    protected $fillable = [
        'property_id',
        'status',
        'name',
        'image',
        'sorting',
    ];

    protected $casts = [
        'sorting' => 'integer',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function amenities(): HasMany
    {
        return $this->hasMany(PropertyAmenity::class, 'property_amenity_id')->orderBy('sorting');
    }
}
