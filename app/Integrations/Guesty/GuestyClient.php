<?php

namespace App\Integrations\Guesty;

use App\Integrations\Guesty\Contracts\GuestyClientInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GuestyClient implements GuestyClientInterface
{
    protected string $openApiUrl;

    protected string $bookingApiUrl;

    protected int $tokenTtl;

    public function __construct()
    {
        $this->openApiUrl = rtrim(config('guesty.open_api_url'), '/');
        $this->bookingApiUrl = rtrim(config('guesty.booking_api_url'), '/');
        $this->tokenTtl = (int) config('guesty.token_ttl', 86400);
    }

    /* ------------------------------------------------------------------ */
    /*  Token Management                                                   */
    /* ------------------------------------------------------------------ */

    public function getOpenApiToken(): string
    {
        return Cache::remember('guesty_open_api_token', $this->tokenTtl - 60, function () {
            $response = Http::asForm()->post('https://open-api.guesty.com/oauth2/token', [
                'grant_type'    => 'client_credentials',
                'scope'         => 'open-api',
                'client_id'     => config('guesty.client_id'),
                'client_secret' => config('guesty.client_secret'),
            ]);

            if (! $response->successful()) {
                Log::error('Guesty Open API token request failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                return '';
            }

            return $response->json('access_token', '');
        });
    }

    public function getBookingEngineToken(): string
    {
        return Cache::remember('guesty_booking_engine_token', $this->tokenTtl - 60, function () {
            $response = Http::asForm()->post($this->bookingApiUrl . '/oauth2/token', [
                'grant_type'    => 'client_credentials',
                'scope'         => 'booking-engine',
                'client_id'     => config('guesty.booking_client_id'),
                'client_secret' => config('guesty.booking_client_secret'),
            ]);

            if (! $response->successful()) {
                Log::error('Guesty Booking Engine token request failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                return '';
            }

            return $response->json('access_token', '');
        });
    }

    /* ------------------------------------------------------------------ */
    /*  Open API HTTP Methods                                              */
    /* ------------------------------------------------------------------ */

    public function openApiGet(string $endpoint, array $query = []): array
    {
        return $this->authenticatedRequest('get', $this->openApiUrl . '/' . ltrim($endpoint, '/'), $query, 'open');
    }

    public function openApiPost(string $endpoint, array $data = []): array
    {
        return $this->authenticatedRequest('post', $this->openApiUrl . '/' . ltrim($endpoint, '/'), $data, 'open');
    }

    public function openApiPut(string $endpoint, array $data = []): array
    {
        return $this->authenticatedRequest('put', $this->openApiUrl . '/' . ltrim($endpoint, '/'), $data, 'open');
    }

    /* ------------------------------------------------------------------ */
    /*  Booking API HTTP Methods                                           */
    /* ------------------------------------------------------------------ */

    public function bookingApiGet(string $endpoint, array $query = []): array
    {
        return $this->authenticatedRequest('get', $this->bookingApiUrl . '/' . ltrim($endpoint, '/'), $query, 'booking');
    }

    public function bookingApiPost(string $endpoint, array $data = []): array
    {
        return $this->authenticatedRequest('post', $this->bookingApiUrl . '/' . ltrim($endpoint, '/'), $data, 'booking');
    }

    /* ------------------------------------------------------------------ */
    /*  Internal                                                           */
    /* ------------------------------------------------------------------ */

    protected function authenticatedRequest(string $method, string $url, array $data, string $tokenType): array
    {
        $token = $tokenType === 'booking'
            ? $this->getBookingEngineToken()
            : $this->getOpenApiToken();

        try {
            $request = Http::withToken($token)->acceptJson();

            $response = $method === 'get'
                ? $request->get($url, $data)
                : $request->$method($url, $data);

            return [
                'status' => $response->status(),
                'data'   => $response->json() ?? [],
            ];
        } catch (\Exception $e) {
            Log::error("Guesty API request failed: {$method} {$url}", [
                'error' => $e->getMessage(),
            ]);

            return [
                'status' => 400,
                'error'  => $e->getMessage(),
            ];
        }
    }
}
