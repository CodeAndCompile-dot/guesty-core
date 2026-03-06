<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyManagementRequest extends Model
{
    protected $table = 'property_management_requests';

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'property_address',
        'property_type',
        'number_of_bedrooms',
        'number_of_bathrooms',
        'what_is_your_rental_goal',
        'what_are_you_looking_to_rent_your_property',
        'is_the_property_currently_closed',
        'message',
    ];
}
