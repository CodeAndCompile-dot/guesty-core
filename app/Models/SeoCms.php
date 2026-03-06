<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoCms extends Model
{
    protected $table = 'seo_pages';

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
        'vacation_three_image',
        'vacation_three_link',
        'vacation_three_title',
        'vacation_two_image',
        'vacation_two_link',
        'vacation_two_title',
        'vacation_one_image',
        'vacation_one_link',
        'vacation_one_title',
        'vacation_four_image',
        'vacation_four_link',
        'vacation_four_title',
        'banner_sub_heading',
        'banner_heading',
        'vacation_heading',
        'vacation_sub_heading',
        'attraction_secion',
        'video_section',
        'vacation_one_alt',
        'vacation_two_alt',
        'vacation_three_alt',
        'vacation_four_alt',
    ];
}
