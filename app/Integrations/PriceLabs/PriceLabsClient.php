<?php

namespace App\Integrations\PriceLabs;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * PriceLabs API client — replaces legacy raw cURL calls.
 */
class PriceLabsClient
{
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = rtrim(config('pricelabs.api_url', 'https://api.pricelabs.co'), '/');
    }

    /**
     * Fetch listing prices from PriceLabs.
     *
     * @param  string  $listingId  The PriceLabs listing ID (property.api_id)
     * @param  string  $pms  The PMS identifier (property.api_pms)
     * @param  string  $apiKey  PriceLabs API key
     * @return array  Decoded JSON response
     */
    public function getListingPrices(string $listingId, string $pms, string $apiKey): array
    {
        try {
            $response = Http::withHeaders([
                'X-API-Key'    => $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post("{$this->apiUrl}/v1/listing_prices", [
                'listings' => [
                    ['id' => $listingId, 'pms' => $pms],
                ],
            ]);

            if ($response->successful()) {
                return $response->json() ?? [];
            }

            Log::warning('PriceLabs API request failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return [];
        } catch (\Throwable $e) {
            Log::error('PriceLabs API error', ['error' => $e->getMessage()]);

            return [];
        }
    }
}
