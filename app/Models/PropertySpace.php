<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertySpace extends Model
{
    use HasFactory;

    protected $table = 'property_spaces';

    protected $fillable = [
        'property_id',
        'space_name',
        'space_image',
        'space_status',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
