<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OurTeam extends Model
{
    protected $table = 'our_teams';

    protected $fillable = [
        'first_name',
        'last_name',
        'image',
        'email',
        'mobile',
        'profile',
        'bannerImage',
        'contactImage',
        'seo_url',
        'longDescription',
        'header_section',
        'footer_section',
        'meta_title',
        'meta_keywords',
        'meta_description',
    ];
}
