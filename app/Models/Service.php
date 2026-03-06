<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';

    protected $fillable = [
        'name',
        'seo_url',
        'shortDescription',
        'mediumDescription',
        'longDescription',
        'description',
        'image',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'templete',
        'bannerImage',
        'publish',
        'header_section',
        'footer_section',
    ];
}
