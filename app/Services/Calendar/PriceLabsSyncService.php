<?php

namespace App\Services\Calendar;

use App\Integrations\PriceLabs\PriceLabsClient;
use App\Models\Property;
use App\Models\PropertyRate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Orchestrates PriceLabs rate synchronisation.
 * Ports ICalController::setPriceLab() with proper error handling and DB transactions.
 */
class PriceLabsSyncService
{
    public function __construct(
        protected PriceLabsClient $client,
    ) {}

    /**
     * Sync PriceLabs rates for all properties that have api_id and api_pms configured.
     *
     * @param  string  $apiKey  PriceLabs API key (from settings: pricelab_access_token)
     */
    public function syncAll(string $apiKey): int
    {
        if (empty($apiKey)) {
            return 0;
        }

        $properties = Property::whereNotNull('api_id')
            ->whereNotNull('api_pms')
            ->get();

        $totalSynced = 0;

        foreach ($properties as $property) {
            if (! $property->api_id || ! $property->api_pms) {
                continue;
            }

            $totalSynced += $this->syncProperty($property, $apiKey);
        }

        return $totalSynced;
    }

    /**
     * Sync PriceLabs rates for a single property.
     */
    public function syncProperty(Property $property, string $apiKey): int
    {
        $response = $this->client->getListingPrices(
            $apiKey,
            $property->api_id,
            $property->api_pms
        );

        if (! $response || ! isset($response[0]['data'])) {
            Log::warning('PriceLabs: No data returned for property', [
                'property_id' => $property->id,
                'api_id'      => $property->api_id,
            ]);

            return 0;
        }

        $count = 0;

        DB::transaction(function () use ($response, $property, &$count) {
            foreach ($response[0]['data'] as $result) {
                $bdate = $result['date'] ?? '';
                $price = $result['price'] ?? '';

                if ($bdate === '' || $price === '') {
                    continue;
                }

                PropertyRate::where([
                    'property_id' => $property->id,
                    'single_date' => $bdate,
                ])->delete();

                PropertyRate::create([
                    'property_id'           => $property->id,
                    'single_date'           => $bdate,
                    'single_date_timestamp' => strtotime($bdate),
                    'price'                 => $price,
                    'is_available'          => 1,
                    'platform_type'         => $property->api_pms,
                    'min_stay'              => $result['min_stay'] ?? null,
                    'base_min_stay'         => $result['min_stay'] ?? null,
                ]);

                $count++;
            }
        });

        return $count;
    }
}
