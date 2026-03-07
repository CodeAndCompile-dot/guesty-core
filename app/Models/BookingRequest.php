<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * BookingRequest – stores every booking / enquiry submitted by a guest.
 *
 * Legacy notes:
 *  - Card data (card_number, card_cvv, etc.) stored in plaintext (PCI violation
 *    carried over from legacy for compatibility — must be encrypted in Phase 8).
 *  - booking_status values: booked, booking-confirmed, rental-aggrement,
 *    booking-cancel, booking-confirmed123 (dead code).
 *  - payment_status values: paid, partially, pending, declined, failed, Stripe key send.
 *  - Boolean-ish enum columns use 'true'/'false' strings (legacy convention).
 */
class BookingRequest extends Model
{
    use HasFactory;

    protected $table = 'booking_requests';

    public $fillable = [
        'property_id',
        'checkin',
        'checkout',
        'total_guests',
        'adults',
        'child',
        'gross_amount',
        'total_night',
        'sub_amount',
        'total_amount',
        'after_total_fees',
        'before_total_fees',
        'request_id',
        'booking_status',
        'email_status',
        'payment_status',
        'welcome_email',
        'review_email',
        'reminder_email',
        'third_reminder_email',
        'checkin_email',
        'checkout_email',
        'firstname',
        'lastname',
        'name',
        'email',
        'mobile',
        'message',
        'ip_address',
        'cancel_reason',
        'note',
        'rental_aggrement_status',
        'rental_aggrement_signature',
        'rental_aggrement_images',
        'total_payment',
        'amount_data',
        'rental_agreement_link',
        'how_many_payment_done',
        'total_pets',
        'pet_fee',
        'guest_fee',
        'rest_guests',
        'single_guest_fee',
        'discount',
        'discount_coupon',
        'after_discount_total',
        'extra_discount',
        'heating_pool_fee',
        'tax',
        'define_tax',
        'pet_fee_type',
        'heating_pool_fee_type',
        'booking_type_admin',
        'rate_api_id',
        'stripe_intent_data_id',
        'stripe_main_payment_method',
        'quote_id',
        'booking_guesty_id',
        'booking_guesty_json',
        'card_number',
        'card_cvv',
        'card_expiry_month',
        'card_expiry_year',
        'address_line_1',
        'city',
        'zipcode',
        'country',
        'new_guest_id',
        'new_guest_object',
        'new_pre_booking_object',
        'new_result_booking_object',
        'new_property_id',
        'new_reservation_id',
        'new_booking_status',
        'color',
        'payment_object',
    ];

    /* ------------------------------------------------------------------ */
    /*  Relationships                                                      */
    /* ------------------------------------------------------------------ */

    /**
     * The property this booking belongs to (local properties table).
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    /**
     * Payments collected for this booking.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'booking_id');
    }
}
