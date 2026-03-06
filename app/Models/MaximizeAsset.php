<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaximizeAsset extends Model
{
    protected $table = 'maximize_assets';

    protected $fillable = [
        'title',
        'image',
        'description',
        'order_no',
    ];
}
