<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WelcomePackage extends Model
{
    protected $table = 'welcome_packages';

    protected $fillable = [
        'name',
        'longDescription',
        'image',
        'bannerImage',
        'location_id',
    ];
}
