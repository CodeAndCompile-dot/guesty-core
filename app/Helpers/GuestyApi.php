<?php

namespace App\Helpers;

use App\Integrations\Guesty\GuestyBookingApi;
use App\Integrations\Guesty\GuestyClient;
use App\Integrations\Guesty\GuestyGuestApi;
use App\Integrations\Guesty\GuestyPaymentApi;
use App\Integrations\Guesty\GuestyPropertyApi;
use App\Integrations\Guesty\GuestyQuoteApi;
use App\Integrations\Guesty\GuestyReviewApi;
use App\Models\BasicSetting;

/**
 * GuestyApi bridge — preserves the legacy \GuestyApi:: facade interface while
 * delegating to the new, properly-architected Guesty integration layer.
 *
 * Every public method here maps 1:1 to a legacy GuestyApi helper method.
 * Controllers call \GuestyApi::methodName() via the facade alias.
 */
class GuestyApi
{
    public function __construct(
        protected GuestyClient $client,
        protected GuestyPropertyApi $propertyApi,
        protected GuestyBookingApi $bookingApi,
        protected GuestyQuoteApi $quoteApi,
        protected GuestyGuestApi $guestApi,
        protected GuestyPaymentApi $paymentApi,
        protected GuestyReviewApi $reviewApi,
    ) {}

    /* ------------------------------------------------------------------ */
    /*  Token management                                                   */
    /* ------------------------------------------------------------------ */

    /**
     * Refresh Open API token and store in basic_settings (legacy behavior).
     */
    public function getToken(): string
    {
        $token = $this->client->getOpenApiToken();

        if ($token) {
            BasicSetting::updateOrCreate(
                ['name' => 'API-TOKEN-DATA'],
                ['value' => $token],
            );
        }

        return $token;
    }

    /**
     * Refresh Booking Engine token and store in basic_settings (legacy behavior).
     */
    public function getBookingToken(): string
    {
        $token = $this->client->getBookingEngineToken();

        if ($token) {
            BasicSetting::updateOrCreate(
                ['name' => 'BOOKING-API-TOKEN-DATA'],
                ['value' => $token],
            );
        }

        return $token;
    }

    /* ------------------------------------------------------------------ */
    /*  Property sync                                                      */
    /* ------------------------------------------------------------------ */

    /**
     * Sync all properties from Guesty API to local DB.
     * Legacy: GuestyApi::getPropertyData()
     */
    public function getPropertyData(): array
    {
        return $this->propertyApi->syncProperties();
    }

    /* ------------------------------------------------------------------ */
    /*  Booking sync                                                       */
    /* ------------------------------------------------------------------ */

    /**
     * Sync all bookings from Guesty API to local DB.
     * Legacy: GuestyApi::getBookingData()
     */
    public function getBookingData(): array
    {
        return $this->bookingApi->syncBookings();
    }

    /* ------------------------------------------------------------------ */
    /*  Review sync                                                        */
    /* ------------------------------------------------------------------ */

    /**
     * Sync all reviews from Guesty API to local DB.
     * Legacy: GuestyApi::getReviewData()
     */
    public function getReviewData(): array
    {
        return $this->reviewApi->syncReviews();
    }

    /* ------------------------------------------------------------------ */
    /*  Availability & Fees                                                */
    /* ------------------------------------------------------------------ */

    /**
     * Get availability/pricing data for a listing.
     * Legacy: GuestyApi::getAVailablityDataData($prop_id)
     */
    public function getAVailablityDataData(string $listingId): array
    {
        return $this->propertyApi->syncAvailability($listingId);
    }

    /**
     * Get calendar fee data for a date range.
     * Legacy: GuestyApi::getCalFeeData($prop_id, $start_date, $end_date)
     */
    public function getCalFeeData(string $listingId, string $startDate, string $endDate): array
    {
        return $this->propertyApi->getCalendarFees($listingId, $startDate, $endDate);
    }

    /**
     * Get additional fee data (financial info) for a listing.
     * Legacy: GuestyApi::getAdditionalFeeData($prop_id)
     */
    public function getAdditionalFeeData(string $listingId): array
    {
        return $this->propertyApi->getAdditionalFees($listingId);
    }

    /**
     * Get all additional fees for a listing.
     * Legacy: GuestyApi::getAdditionalFeeDataAll($prop_id)
     */
    public function getAdditionalFeeDataAll(string $listingId): array
    {
        return $this->propertyApi->getListingAdditionalFees($listingId);
    }

    /**
     * Get account-level additional fees.
     * Legacy: GuestyApi::getCalAddFeeData()
     */
    public function getCalAddFeeData(): array
    {
        return $this->propertyApi->getAccountAdditionalFees();
    }

    /**
     * Search available listings.
     * Legacy: GuestyApi::getSearchAvailability($checkin, $checkout, $total_guest)
     */
    public function getSearchAvailability(string $checkIn, string $checkOut, int $guests): array
    {
        return $this->propertyApi->searchAvailability($checkIn, $checkOut, $guests);
    }

    /* ------------------------------------------------------------------ */
    /*  Guest management                                                   */
    /* ------------------------------------------------------------------ */

    /**
     * Create a guest in Guesty.
     * Legacy: GuestyApi::createGuest($firstname, $lastname, $email, $mobile)
     */
    public function createGuest(string $firstName, string $lastName, string $email, string $mobile): array
    {
        return $this->guestApi->createGuest($firstName, $lastName, $email, $mobile);
    }

    /**
     * Get guest data by ID.
     * Legacy: GuestyApi::getGuestData($id)
     */
    public function getGuestData(string $guestId): array
    {
        return $this->guestApi->getGuest($guestId);
    }

    /* ------------------------------------------------------------------ */
    /*  Booking creation                                                   */
    /* ------------------------------------------------------------------ */

    /**
     * Create a new reservation in Guesty (inquiry flow).
     * Legacy: GuestyApi::newBookingCreate($data) — legacy passes JSON string.
     */
    public function newBookingCreate(string|array $data): array
    {
        if (is_string($data)) {
            $data = json_decode($data, true) ?? [];
        }

        return $this->bookingApi->createReservation($data);
    }

    /**
     * Confirm a reservation.
     * Legacy: GuestyApi::confirmBooking($id)
     */
    public function confirmBooking(string $reservationId): array
    {
        return $this->bookingApi->confirmReservation($reservationId);
    }

    /**
     * Instant book from a Booking Engine quote.
     * Legacy: GuestyApi::setBookingDataNew($first_name, $last_name, $email, $mobile, $rate_plan_id, $stripe_main_payment_method, $quote_id)
     */
    public function setBookingDataNew(
        string $firstName,
        string $lastName,
        string $email,
        string $mobile,
        ?string $ratePlanId,
        string $paymentMethod,
        string $quoteId,
    ): array {
        $data = [
            'guest' => [
                'firstName' => $firstName,
                'lastName'  => $lastName,
                'email'     => $email,
                'phone'     => $mobile,
            ],
            'paymentMethod' => [
                'token' => $paymentMethod,
            ],
        ];

        if ($ratePlanId) {
            $data['ratePlanId'] = $ratePlanId;
        }

        return $this->client->bookingApiPost("api/reservations/quotes/{$quoteId}/instant", $data);
    }

    /**
     * Create booking with full money/guest data inline.
     * Legacy: GuestyApi::saveBookingUsingGuestyData(11 params)
     */
    public function saveBookingUsingGuestyData(
        string $propertyId,
        float $fareAccommodation,
        float $fareCleaning,
        string $startDate,
        string $endDate,
        string $firstName,
        string $lastName,
        string $email,
        string $mobile,
        int $totalGuests,
        float $totalAmount,
    ): array {
        return $this->bookingApi->createReservation([
            'listingId'               => $propertyId,
            'checkInDateLocalized'    => $startDate,
            'checkOutDateLocalized'   => $endDate,
            'status'                  => 'inquiry',
            'guest'                   => [
                'firstName' => $firstName,
                'lastName'  => $lastName,
                'phone'     => $mobile,
                'email'     => $email,
            ],
            'money' => [
                'fareAccommodation' => (string) $fareAccommodation,
                'fareCleaning'      => (string) $fareCleaning,
                'currency'          => 'USD',
            ],
        ]);
    }

    /* ------------------------------------------------------------------ */
    /*  Payment                                                            */
    /* ------------------------------------------------------------------ */

    /**
     * Attach a payment method to a guest.
     * Legacy: GuestyApi::paymentAttached($guestid, $paymentprovideid, $paymentid, $reservationid)
     */
    public function paymentAttached(string $guestId, string $paymentProviderId, string $paymentId, string $reservationId): array
    {
        return $this->paymentApi->attachPaymentMethod($guestId, $paymentProviderId, $paymentId, $reservationId);
    }

    /**
     * Record a Stripe payment against a reservation.
     * Legacy: GuestyApi::paidAPi($reg_id, $amount, $stripe_id)
     */
    public function paidAPi(string $reservationId, float $amount, string $stripeId): array
    {
        return $this->paymentApi->recordPayment($reservationId, $amount, $stripeId);
    }

    /**
     * Get the payment provider for a listing (Booking Engine).
     * Legacy: GuestyApi::getBookingPaymentid($listingID)
     */
    public function getBookingPaymentid(string $listingId): array
    {
        return $this->paymentApi->getListingPaymentProvider($listingId);
    }

    /**
     * Get payment providers summary.
     * Legacy: GuestyApi::customAPI()
     */
    public function customAPI(): array
    {
        return $this->paymentApi->getPaymentProvidersSummary();
    }

    /* ------------------------------------------------------------------ */
    /*  Quotes                                                             */
    /* ------------------------------------------------------------------ */

    /**
     * Create a detailed quote (7-param version with adult/child breakdown).
     * Legacy: GuestyApi::getQuoteNewNew($guestCount, $adults, $child, $checkin, $checkout, $listingid, $coupon)
     *
     * Note: In legacy PHP, there were two methods with similar names:
     * - getQuoteNewNew (7 params) — the last definition (5 params) wins.
     * We resolve this by checking param count.
     */
    public function getQuoteNewNew(
        int $guestCount,
        int|string $adultsOrCheckIn,
        int|string $childOrCheckOut,
        string $checkInOrListingId,
        ?string $checkOutOrCoupon = null,
        ?string $listingId = null,
        ?string $coupon = null,
    ): array {
        // 7-param call: getQuoteNewNew($count, $adults, $child, $checkin, $checkout, $listingid, $coupon)
        if ($listingId !== null) {
            return $this->quoteApi->createDetailedQuote(
                $guestCount,
                (int) $adultsOrCheckIn,
                (int) $childOrCheckOut,
                $checkInOrListingId,
                $checkOutOrCoupon,
                $listingId,
                $coupon,
            );
        }

        // 5-param call: getQuouteNewNew($count, $checkin, $checkout, $listingid, $coupon)
        return $this->quoteApi->createSimpleQuote(
            $guestCount,
            (string) $adultsOrCheckIn,
            (string) $childOrCheckOut,
            $checkInOrListingId,
            $checkOutOrCoupon,
        );
    }

    /**
     * Alias for 5-param simple quote (legacy duplicate name).
     * Legacy: GuestyApi::getQuouteNewNew($guestCount, $checkin, $checkout, $listingid, $coupon)
     */
    public function getQuouteNewNew(
        int $guestCount,
        string $checkIn,
        string $checkOut,
        string $listingId,
        ?string $coupon = null,
    ): array {
        return $this->quoteApi->createSimpleQuote($guestCount, $checkIn, $checkOut, $listingId, $coupon);
    }

    /**
     * Create a Booking Engine quote.
     * Legacy: GuestyApi::getQuouteNew($guestCount, $checkin, $checkout, $listingid, $coupon)
     */
    public function getQuouteNew(
        int $guestCount,
        string $checkIn,
        string $checkOut,
        string $listingId,
        ?string $coupon = null,
    ): array {
        return $this->quoteApi->createBookingEngineQuote($guestCount, $checkIn, $checkOut, $listingId, $coupon);
    }
}
