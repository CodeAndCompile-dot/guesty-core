<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PropertyRoom extends Model
{
    use HasFactory;

    protected $table = 'property_rooms';

    protected $fillable = [
        'property_id',
        'room_title',
        'room_sub_title',
        'room_description',
        'room_status',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PropertyRoomItem::class, 'room_id');
    }
}
