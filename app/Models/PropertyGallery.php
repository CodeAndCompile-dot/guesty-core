<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyGallery extends Model
{
    use HasFactory;

    protected $table = 'property_galleries';

    protected $fillable = [
        'property_id',
        'status',
        'image',
        'sorting',
        'caption',
    ];

    protected function casts(): array
    {
        return [
            'sorting' => 'integer',
        ];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
