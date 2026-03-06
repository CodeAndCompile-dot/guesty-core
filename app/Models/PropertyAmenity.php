<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyAmenity extends Model
{
    use HasFactory;

    protected $table = 'property_amenities';

    protected $fillable = [
        'property_amenity_id',
        'name',
        'status',
        'image',
        'sorting',
    ];

    protected $casts = [
        'sorting' => 'integer',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(PropertyAmenityGroup::class, 'property_amenity_id');
    }
}
