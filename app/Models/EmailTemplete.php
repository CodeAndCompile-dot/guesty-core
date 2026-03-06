<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplete extends Model
{
    protected $table = 'email_templetes';

    protected $fillable = [
        'email_type',
        'email_subject',
        'email_body',
    ];
}
