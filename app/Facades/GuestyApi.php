<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * GuestyApi facade — bridges legacy \GuestyApi:: calls to the new integration layer.
 *
 * @method static string getToken()
 * @method static string getBookingToken()
 * @method static array getPropertyData()
 * @method static array getBookingData()
 * @method static array getReviewData()
 * @method static array getAVailablityDataData(string $listingId)
 * @method static array getCalFeeData(string $listingId, string $startDate, string $endDate)
 * @method static array getAdditionalFeeData(string $listingId)
 * @method static array getAdditionalFeeDataAll(string $listingId)
 * @method static array getCalAddFeeData()
 * @method static array getSearchAvailability(string $checkIn, string $checkOut, int $guests)
 * @method static array createGuest(string $firstName, string $lastName, string $email, string $mobile)
 * @method static array getGuestData(string $guestId)
 * @method static array newBookingCreate(string|array $data)
 * @method static array confirmBooking(string $reservationId)
 * @method static array setBookingDataNew(string $firstName, string $lastName, string $email, string $mobile, ?string $ratePlanId, string $paymentMethod, string $quoteId)
 * @method static array paymentAttached(string $guestId, string $paymentProviderId, string $paymentId, string $reservationId)
 * @method static array paidAPi(string $reservationId, float $amount, string $stripeId)
 * @method static array getBookingPaymentid(string $listingId)
 * @method static array customAPI()
 * @method static array getQuoteNewNew(int $guestCount, int|string $adultsOrCheckIn, int|string $childOrCheckOut, string $checkInOrListingId, ?string $checkOutOrCoupon = null, ?string $listingId = null, ?string $coupon = null)
 * @method static array getQuouteNewNew(int $guestCount, string $checkIn, string $checkOut, string $listingId, ?string $coupon = null)
 * @method static array getQuouteNew(int $guestCount, string $checkIn, string $checkOut, string $listingId, ?string $coupon = null)
 *
 * @see \App\Helpers\GuestyApi
 */
class GuestyApi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'GuestyApi';
    }
}
