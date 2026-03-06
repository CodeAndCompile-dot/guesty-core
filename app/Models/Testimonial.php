<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $table = 'testimonials';

    protected $fillable = [
        'name',
        'message',
        'image',
        'email',
        'mobile',
        'profile',
        'score',
        'stay_date',
        'property_id',
        'status',
        'ordering',
    ];
}
