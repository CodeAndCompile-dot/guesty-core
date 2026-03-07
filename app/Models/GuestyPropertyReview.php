<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestyPropertyReview extends Model
{
    protected $table = 'guesty_property_reviews';

    protected $fillable = [
        '_id',
        'externalReviewId',
        'accountId',
        'channelId',
        'createdAt',
        'createdAtGuesty',
        'externalListingId',
        'externalReservationId',
        'guestId',
        'listingId',
        'rawReview',
        'reservationId',
        'updatedAt',
        'updatedAtGuesty',
        'reviewReplies',
        'full_name',
        'all_data',
        'guest_data',
    ];
}
