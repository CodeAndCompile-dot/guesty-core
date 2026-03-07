<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogCategory extends Model
{
    protected $table = 'blog_categories';

    protected $fillable = [
        'title',
        'seo_url',
        'image',
        'shortDescription',
        'benefits',
        'longDescription',
        'isHome',
        'publish',
        'isParent',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'bannerImage',
        'ordering',
        'templete',
        'title_ger',
        'shortDescription_ger',
        'longDescription_ger',
        'meta_title_ger',
        'meta_keywords_ger',
        'meta_description_ger',
        'benefits_ger',
    ];

    /* ------------------------------------------------------------------ */
    /*  Relationships                                                      */
    /* ------------------------------------------------------------------ */

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'blog_category_id');
    }
}
