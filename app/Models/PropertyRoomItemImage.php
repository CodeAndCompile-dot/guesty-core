<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyRoomItemImage extends Model
{
    use HasFactory;

    protected $table = 'property_room_item_images';

    protected $fillable = [
        'sub_room_id',
        'sub_room_image',
    ];

    public function roomItem(): BelongsTo
    {
        return $this->belongsTo(PropertyRoomItem::class, 'sub_room_id');
    }
}
