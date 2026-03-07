<?php

namespace App\Integrations\Guesty;

use App\Integrations\Guesty\Contracts\GuestyClientInterface;
use App\Models\GuestyPropertyReview;

class GuestyReviewApi
{
    public function __construct(
        protected GuestyClientInterface $client,
        protected GuestyGuestApi $guestApi,
    ) {}

    /**
     * Sync all reviews from Guesty (destructive — truncates and reimports).
     * Preserves legacy behavior: fetches guest data per review for full_name.
     */
    public function syncReviews(): array
    {
        GuestyPropertyReview::truncate();

        $skip = 0;
        $limit = 100;
        $total = 0;

        do {
            $response = $this->client->openApiGet('reviews', [
                'limit' => $limit,
                'skip'  => $skip,
            ]);

            if (($response['status'] ?? 0) !== 200) {
                return $response;
            }

            $results = $response['data']['data'] ?? [];

            foreach ($results as $review) {
                $this->insertReview($review);
                $total++;
            }

            $skip += $limit;
        } while (count($results) >= $limit);

        return ['status' => 200, 'message' => 'success', 'count' => $total];
    }

    /* ------------------------------------------------------------------ */
    /*  Internal                                                           */
    /* ------------------------------------------------------------------ */

    protected function insertReview(array $review): void
    {
        $fullName = null;
        $guestData = null;

        // Fetch guest data for full_name (legacy behavior)
        if (! empty($review['guestId'])) {
            $guestResponse = $this->guestApi->getGuest($review['guestId']);

            if (($guestResponse['status'] ?? 0) === 200) {
                $guest = $guestResponse['data'] ?? [];
                $guestData = json_encode($guest);
                $fullName = trim(($guest['fullName'] ?? '') ?: (($guest['firstName'] ?? '') . ' ' . ($guest['lastName'] ?? '')));
            }
        }

        GuestyPropertyReview::create([
            '_id'                    => $review['_id'] ?? null,
            'externalReviewId'       => $review['externalReviewId'] ?? null,
            'accountId'              => $review['accountId'] ?? null,
            'channelId'              => $review['channelId'] ?? null,
            'createdAt'              => $review['createdAt'] ?? null,
            'createdAtGuesty'        => $review['createdAtGuesty'] ?? null,
            'externalListingId'      => $review['externalListingId'] ?? null,
            'externalReservationId'  => $review['externalReservationId'] ?? null,
            'guestId'                => $review['guestId'] ?? null,
            'listingId'              => $review['listingId'] ?? null,
            'rawReview'              => isset($review['rawReview']) ? json_encode($review['rawReview']) : null,
            'reservationId'          => $review['reservationId'] ?? null,
            'updatedAt'              => $review['updatedAt'] ?? null,
            'updatedAtGuesty'        => $review['updatedAtGuesty'] ?? null,
            'reviewReplies'          => isset($review['reviewReplies']) ? json_encode($review['reviewReplies']) : null,
            'full_name'              => $fullName,
            'guest_data'             => $guestData,
            'all_data'               => json_encode($review),
        ]);
    }
}
