<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

    protected $table = 'locations';

    protected $fillable = [
        'name',
        'seo_url',
        'shortDescription',
        'mediumDescription',
        'longDescription',
        'description',
        'image',
        'attraction_image',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'templete',
        'bannerImage',
        'publish',
        'header_section',
        'footer_section',
        'status',
        'is_parent',
        'ordering',
    ];

    protected $casts = [
        'ordering' => 'integer',
        'is_parent' => 'integer',
    ];

    /* ------------------------------------------------------------------ */
    /*  Relationships                                                      */
    /* ------------------------------------------------------------------ */

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'location_id');
    }
}
