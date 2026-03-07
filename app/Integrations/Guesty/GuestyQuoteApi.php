<?php

namespace App\Integrations\Guesty;

use App\Integrations\Guesty\Contracts\GuestyClientInterface;

class GuestyQuoteApi
{
    public function __construct(
        protected GuestyClientInterface $client,
    ) {}

    /**
     * Create a quote via the Open API with full guest breakdown (adults + children).
     *
     * Legacy: getQuoteNewNew (7 params)
     */
    public function createDetailedQuote(
        int $guestCount,
        int $adults,
        int $children,
        string $checkIn,
        string $checkOut,
        string $listingId,
        ?string $coupon = null,
    ): array {
        $data = [
            'listingId'      => $listingId,
            'checkInDateLocalized'  => $checkIn,
            'checkOutDateLocalized' => $checkOut,
            'numberOfGuests' => [
                'numberOfAdults'   => $adults,
                'numberOfChildren' => $children,
                'numberOfInfants'  => 0,
            ],
        ];

        if ($coupon) {
            $data['couponCode'] = $coupon;
        }

        return $this->client->openApiPost('quotes', $data);
    }

    /**
     * Create a quote via the Open API with simple guest count (no adult/child breakdown).
     *
     * Legacy: getQuouteNewNew (5 params) — duplicate method name, last definition wins.
     */
    public function createSimpleQuote(
        int $guestCount,
        string $checkIn,
        string $checkOut,
        string $listingId,
        ?string $coupon = null,
    ): array {
        $data = [
            'listingId'      => $listingId,
            'checkInDateLocalized'  => $checkIn,
            'checkOutDateLocalized' => $checkOut,
            'numberOfGuests' => $guestCount,
        ];

        if ($coupon) {
            $data['couponCode'] = $coupon;
        }

        return $this->client->openApiPost('quotes', $data);
    }

    /**
     * Create a quote via the Booking Engine API.
     *
     * Legacy: getQuouteNew (5 params)
     */
    public function createBookingEngineQuote(
        int $guestCount,
        string $checkIn,
        string $checkOut,
        string $listingId,
        ?string $coupon = null,
    ): array {
        $data = [
            'listingId' => $listingId,
            'checkIn'   => $checkIn,
            'checkOut'  => $checkOut,
            'guests'    => $guestCount,
        ];

        if ($coupon) {
            $data['coupon'] = $coupon;
        }

        return $this->client->bookingApiPost('api/reservations/quotes', $data);
    }
}
