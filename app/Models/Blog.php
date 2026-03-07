<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Blog extends Model
{
    protected $table = 'blogs';

    protected $fillable = [
        'title',
        'seo_url',
        'image',
        'publish',
        'longDescription',
        'shortDescription',
        'meta_description',
        'meta_keywords',
        'meta_title',
        'featureImage',
        'blog_category_id',
        'agent_id',
        'title_ger',
        'longDescription_ger',
        'shortDescription_ger',
        'meta_description_ger',
        'meta_keywords_ger',
        'meta_title_ger',
        'status',
    ];

    /* ------------------------------------------------------------------ */
    /*  Relationships                                                      */
    /* ------------------------------------------------------------------ */

    public function blogCategory(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }
}
