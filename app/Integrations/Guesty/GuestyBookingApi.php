<?php

namespace App\Integrations\Guesty;

use App\Integrations\Guesty\Contracts\GuestyClientInterface;
use App\Models\GuestyPropertyBooking;

class GuestyBookingApi
{
    public function __construct(
        protected GuestyClientInterface $client,
    ) {}

    /**
     * Sync all reservations from Guesty (destructive — truncates and reimports).
     * Preserves legacy behavior exactly.
     */
    public function syncBookings(): array
    {
        GuestyPropertyBooking::truncate();

        $skip = 0;
        $limit = 10;
        $total = 0;

        do {
            $response = $this->client->openApiGet('reservations', [
                'limit' => $limit,
                'skip'  => $skip,
            ]);

            if (($response['status'] ?? 0) !== 200) {
                return $response;
            }

            $results = $response['data']['results'] ?? [];

            foreach ($results as $booking) {
                $this->insertBooking($booking);
                $total++;
            }

            $skip += $limit;
        } while (count($results) >= $limit);

        return ['status' => 200, 'message' => 'success', 'count' => $total];
    }

    /**
     * Create a new reservation in Guesty.
     */
    public function createReservation(array $data): array
    {
        return $this->client->openApiPost('reservations', $data);
    }

    /**
     * Confirm a reservation by setting status to "confirmed".
     */
    public function confirmReservation(string $reservationId): array
    {
        return $this->client->openApiPut("reservations/{$reservationId}", [
            'status' => 'confirmed',
        ]);
    }

    /**
     * Get a single booking/reservation from Guesty.
     */
    public function getReservation(string $reservationId): array
    {
        return $this->client->openApiGet("reservations/{$reservationId}");
    }

    /* ------------------------------------------------------------------ */
    /*  Internal                                                           */
    /* ------------------------------------------------------------------ */

    protected function insertBooking(array $booking): void
    {
        GuestyPropertyBooking::create([
            '_id'              => $booking['_id'] ?? null,
            'integration'      => isset($booking['integration']) ? json_encode($booking['integration']) : null,
            'confirmationCode' => $booking['confirmationCode'] ?? null,
            'checkIn'          => $booking['checkIn'] ?? null,
            'checkOut'         => $booking['checkOut'] ?? null,
            'start_date'       => isset($booking['checkIn']) ? date('Y-m-d', strtotime($booking['checkIn'])) : null,
            'end_date'         => isset($booking['checkOut']) ? date('Y-m-d', strtotime($booking['checkOut'])) : null,
            'listingId'        => $booking['listingId'] ?? null,
            'guest'            => isset($booking['guest']) ? json_encode($booking['guest']) : null,
            'accountId'        => $booking['accountId'] ?? null,
            'guestId'          => $booking['guestId'] ?? null,
            'listing'          => isset($booking['listing']) ? json_encode($booking['listing']) : null,
            'all_data'         => json_encode($booking),
        ]);
    }
}
