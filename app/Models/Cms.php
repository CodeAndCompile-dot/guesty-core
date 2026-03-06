<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cms extends Model
{
    protected $table = 'cms';

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
        'seo_section',
        'image_2',
        'image_3',
        'faq_title',
        'faq_desction',
        'faq_image',
        'ogimage',
        'strip_title',
        'strip_desction',
        'strip_anchor',
        'strip_image',
        'about_image1',
        'about_image2',
        'owner_image',
        'section_image',
        'section_desc',
        'section2_desc',
        'section2_img',
        'section3_desc',
        'section4_desc',
        'section4_main_img',
        'section4_sub_heading1',
        'section4_sub_desc1',
        'section4_sub_icon1',
        'section4_sub_heading2',
        'section4_sub_desc2',
        'section4_sub_icon2',
        'section4_sub_heading3',
        'section4_sub_desc3',
        'section4_sub_icon3',
        'section5_desc',
        'section5_main_img',
        'section5_sub_heading1',
        'section5_sub_desc1',
        'section5_sub_icon1',
        'section5_sub_heading2',
        'section5_sub_desc2',
        'section5_sub_icon2',
        'section5_sub_heading3',
        'section5_sub_desc3',
        'section5_sub_icon3',
        'section6_desc',
        'section6_img1',
        'section6_img2',
        'section6_img3',
    ];

    public function sliders()
    {
        return $this->hasMany(Slider::class, 'cms_id');
    }
}
