<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestyPropertyPrice extends Model
{
    protected $table = 'guesty_property_prices';

    protected $fillable = [
        'property_id',
        'prices',
        'monthlyPriceFactor',
        'weeklyPriceFactor',
        'currency',
        'basePrice',
        'weekendBasePrice',
        'weekendDays',
        'securityDepositFee',
        'guestsIncludedInRegularFee',
        'extraPersonFee',
        'cleaningFee',
    ];
}
