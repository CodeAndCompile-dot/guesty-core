<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestyAvailabilityPrice extends Model
{
    protected $table = 'guesty_availablity_prices';

    protected $fillable = [
        'start_date',
        'listingId',
        'price',
        'minNights',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'price'      => 'float',
            'minNights'  => 'integer',
        ];
    }
}
