<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table = 'sliders';

    protected $fillable = [
        'title',
        'link',
        'image',
        'cms_id',
        'description',
        'status',
    ];

    public function cms()
    {
        return $this->belongsTo(Cms::class, 'cms_id');
    }
}
