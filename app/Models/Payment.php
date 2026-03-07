<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Payment – records each payment transaction for a booking.
 *
 * Legacy notes:
 *  - type: 'stripe' or 'paypal'
 *  - status: 'pending', 'succeeded', 'failed', etc.
 */
class Payment extends Model
{
    protected $table = 'payments';

    public $fillable = [
        'booking_id',
        'receipt_url',
        'customer_id',
        'balance_transaction',
        'tran_id',
        'description',
        'status',
        'type',
        'amount',
    ];

    /* ------------------------------------------------------------------ */
    /*  Relationships                                                      */
    /* ------------------------------------------------------------------ */

    /**
     * The booking this payment belongs to.
     */
    public function bookingRequest(): BelongsTo
    {
        return $this->belongsTo(BookingRequest::class, 'booking_id');
    }
}
