<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestyPropertyBooking extends Model
{
    protected $table = 'guesty_property_bookings';

    protected $fillable = [
        '_id',
        'integration',
        'confirmationCode',
        'checkIn',
        'checkOut',
        'start_date',
        'end_date',
        'listingId',
        'guest',
        'accountId',
        'guestId',
        'listing',
        'all_data',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];
}
