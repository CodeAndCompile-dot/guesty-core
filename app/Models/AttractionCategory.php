<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttractionCategory extends Model
{
    protected $table = 'attraction_categories';

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
        'category_id',
    ];

    /* ------------------------------------------------------------------ */
    /*  Relationships                                                      */
    /* ------------------------------------------------------------------ */

    /**
     * Parent category (self-referencing).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'category_id');
    }

    /**
     * Child categories.
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'category_id');
    }

    /**
     * Attractions in this category.
     */
    public function attractions(): HasMany
    {
        return $this->hasMany(Attraction::class, 'category_id');
    }
}
