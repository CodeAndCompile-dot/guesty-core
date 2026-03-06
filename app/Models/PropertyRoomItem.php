<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PropertyRoomItem extends Model
{
    use HasFactory;

    protected $table = 'property_room_items';

    protected $fillable = [
        'room_id',
        'sub_room_title',
        'sub_room_sub_title',
        'sub_room_description',
        'sub_room_status',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(PropertyRoom::class, 'room_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(PropertyRoomItemImage::class, 'sub_room_id');
    }
}
