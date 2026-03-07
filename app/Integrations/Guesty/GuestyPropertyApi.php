<?php

namespace App\Integrations\Guesty;

use App\Integrations\Guesty\Contracts\GuestyClientInterface;
use App\Models\GuestyAvailabilityPrice;
use App\Models\GuestyProperty;
use App\Models\GuestyPropertyPrice;
use Carbon\Carbon;

class GuestyPropertyApi
{
    public function __construct(
        protected GuestyClientInterface $client,
    ) {}

    /**
     * Fetch all active/listed properties from Guesty and sync to local DB.
     */
    public function syncProperties(): array
    {
        $response = $this->client->openApiGet('listings', [
            'active'    => 'true',
            'pmsActive' => 'true',
            'listed'    => 'true',
        ]);

        if (($response['status'] ?? 0) !== 200) {
            return $response;
        }

        $results = $response['data']['results'] ?? [];

        foreach ($results as $listing) {
            $this->upsertProperty($listing);
        }

        return ['status' => 200, 'message' => 'success', 'count' => count($results)];
    }

    /**
     * Fetch availability/pricing calendar for a listing (1000 days).
     */
    public function syncAvailability(string $listingId): array
    {
        $startDate = Carbon::now()->format('Y-m-d');
        $endDate = Carbon::now()->addDays(1000)->format('Y-m-d');

        $response = $this->client->openApiGet("availability-pricing/api/calendar/listings/{$listingId}", [
            'startDate' => $startDate,
            'endDate'   => $endDate,
        ]);

        if (($response['status'] ?? 0) !== 200) {
            return $response;
        }

        // Delete existing availability data and re-create
        GuestyAvailabilityPrice::where('listingId', $listingId)->delete();

        $days = $response['data']['data']['days'] ?? [];
        foreach ($days as $day) {
            GuestyAvailabilityPrice::create([
                'start_date' => $day['date'] ?? null,
                'listingId'  => $listingId,
                'price'      => $day['price'] ?? null,
                'minNights'  => $day['minNights'] ?? null,
                'status'     => $day['status'] ?? null,
            ]);
        }

        return ['status' => 200, 'message' => 'success', 'days' => count($days)];
    }

    /**
     * Get calendar fee data for a specific date range.
     */
    public function getCalendarFees(string $listingId, string $startDate, string $endDate): array
    {
        $adjustedEnd = Carbon::parse($endDate)->subDay()->format('Y-m-d');

        return $this->client->openApiGet("availability-pricing/api/calendar/listings/{$listingId}", [
            'startDate'        => $startDate,
            'endDate'          => $adjustedEnd,
            'includeAllotment' => 'true',
        ]);
    }

    /**
     * Get additional fee data for a listing (financial info).
     */
    public function getAdditionalFees(string $listingId): array
    {
        return $this->client->openApiGet("financials/listing/{$listingId}");
    }

    /**
     * Get all additional fees for a listing.
     */
    public function getListingAdditionalFees(string $listingId): array
    {
        return $this->client->openApiGet("additional-fees/listing/{$listingId}");
    }

    /**
     * Get account-level additional fees.
     */
    public function getAccountAdditionalFees(): array
    {
        return $this->client->openApiGet('additional-fees/account');
    }

    /**
     * Search available listings for given dates and guest count.
     */
    public function searchAvailability(string $checkIn, string $checkOut, int $guests): array
    {
        return $this->client->openApiGet('listings', [
            'active'    => 'true',
            'pmsActive' => 'true',
            'listed'    => 'true',
            'available' => json_encode([
                'checkIn'      => $checkIn,
                'checkOut'     => $checkOut,
                'minOccupancy' => $guests,
            ]),
        ]);
    }

    /* ------------------------------------------------------------------ */
    /*  Internal                                                           */
    /* ------------------------------------------------------------------ */

    protected function upsertProperty(array $listing): void
    {
        $listingId = $listing['_id'] ?? null;
        if (! $listingId) {
            return;
        }

        $data = [
            '_id'                    => $listingId,
            'title'                  => $listing['title'] ?? null,
            'nickname'               => $listing['nickname'] ?? null,
            'propertyType'           => $listing['propertyType'] ?? null,
            'roomType'               => $listing['roomType'] ?? null,
            'type'                   => $listing['type'] ?? null,
            'bedrooms'               => $listing['bedrooms'] ?? null,
            'bathrooms'              => $listing['bathrooms'] ?? null,
            'beds'                   => $listing['beds'] ?? null,
            'accommodates'           => $listing['accommodates'] ?? null,
            'active'                 => isset($listing['active']) ? ($listing['active'] ? 'true' : 'false') : null,
            'isListed'               => isset($listing['listed']) ? ($listing['listed'] ? 'true' : 'false') : null,
            'picture'                => isset($listing['picture']) ? json_encode($listing['picture']) : null,
            'pictures'               => isset($listing['pictures']) ? json_encode($listing['pictures']) : null,
            'summary'                => $listing['publicDescription']['summary'] ?? null,
            'space'                  => $listing['publicDescription']['space'] ?? null,
            'access'                 => $listing['publicDescription']['access'] ?? null,
            'interactionWithGuests'  => $listing['publicDescription']['interactionWithGuests'] ?? null,
            'neighborhood'           => $listing['publicDescription']['neighborhood'] ?? null,
            'transit'                => $listing['publicDescription']['transit'] ?? null,
            'notes'                  => $listing['publicDescription']['notes'] ?? null,
            'houseRules'             => $listing['publicDescription']['houseRules'] ?? null,
            'publicDescription'      => isset($listing['publicDescription']) ? json_encode($listing['publicDescription']) : null,
            'privateDescription'     => isset($listing['privateDescription']) ? json_encode($listing['privateDescription']) : null,
            'terms'                  => isset($listing['terms']) ? json_encode($listing['terms']) : null,
            'terms_min_night'        => $listing['terms']['minNights'] ?? null,
            'terms_max_night'        => $listing['terms']['maxNights'] ?? null,
            'prices'                 => isset($listing['prices']) ? json_encode($listing['prices']) : null,
            'amenities'              => isset($listing['amenities']) ? json_encode($listing['amenities']) : null,
            'amenitiesNotIncluded'   => isset($listing['amenitiesNotIncluded']) ? json_encode($listing['amenitiesNotIncluded']) : null,
            'address'                => isset($listing['address']) ? json_encode($listing['address']) : null,
            'defaultCheckInTime'     => $listing['defaultCheckInTime'] ?? null,
            'defaultCheckInEndTime'  => $listing['defaultCheckInEndTime'] ?? null,
            'defaultCheckOutTime'    => $listing['defaultCheckOutTime'] ?? null,
            'accountId'              => $listing['accountId'] ?? null,
            'guests'                 => $listing['accommodates'] ?? 0,
            'all_data'               => json_encode($listing),
        ];

        $existing = GuestyProperty::where('_id', $listingId)->first();

        if ($existing) {
            $existing->update($data);
        } else {
            GuestyProperty::create($data);
        }

        // Sync price data
        $this->syncPropertyPrice($listingId, $listing);
    }

    protected function syncPropertyPrice(string $listingId, array $listing): void
    {
        $prices = $listing['prices'] ?? [];

        GuestyPropertyPrice::where('property_id', $listingId)->delete();

        GuestyPropertyPrice::create([
            'property_id'                 => $listingId,
            'prices'                      => json_encode($prices),
            'monthlyPriceFactor'          => $prices['monthlyPriceFactor'] ?? null,
            'weeklyPriceFactor'           => $prices['weeklyPriceFactor'] ?? null,
            'currency'                    => $prices['currency'] ?? null,
            'basePrice'                   => $prices['basePrice'] ?? null,
            'weekendBasePrice'            => $prices['weekendBasePrice'] ?? null,
            'weekendDays'                 => isset($prices['weekendDays']) ? json_encode($prices['weekendDays']) : null,
            'securityDepositFee'          => $prices['securityDepositFee'] ?? null,
            'guestsIncludedInRegularFee'  => $prices['guestsIncludedInRegularFee'] ?? null,
            'extraPersonFee'              => $prices['extraPersonFee'] ?? null,
            'cleaningFee'                 => $prices['cleaningFee'] ?? null,
        ]);
    }
}
