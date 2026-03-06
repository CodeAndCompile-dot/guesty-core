<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactusRequest extends Model
{
    protected $table = 'contactus_requests';

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'message',
        'date_of_request',
        'budget',
        'guests',
    ];
}
