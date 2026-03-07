<?php

namespace Tests\Unit\Integrations\Guesty;

use App\Integrations\Guesty\GuestyClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GuestyClientTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'guesty.client_id'             => 'test-client-id',
            'guesty.client_secret'         => 'test-client-secret',
            'guesty.booking_client_id'     => 'test-booking-id',
            'guesty.booking_client_secret' => 'test-booking-secret',
            'guesty.open_api_url'          => 'https://open-api.guesty.com/v1',
            'guesty.booking_api_url'       => 'https://booking.guesty.com',
            'guesty.token_ttl'             => 86400,
        ]);

        Cache::flush();
    }

    public function test_get_open_api_token_fetches_and_caches(): void
    {
        Http::fake([
            'open-api.guesty.com/oauth2/token' => Http::response([
                'access_token' => 'test-open-token',
                'token_type'   => 'Bearer',
            ], 200),
        ]);

        $client = new GuestyClient();
        $token = $client->getOpenApiToken();

        $this->assertEquals('test-open-token', $token);

        // Second call should use cache — no new HTTP request
        Http::fake([
            'open-api.guesty.com/oauth2/token' => Http::response([
                'access_token' => 'different-token',
            ], 200),
        ]);

        $token2 = $client->getOpenApiToken();
        $this->assertEquals('test-open-token', $token2);
    }

    public function test_get_booking_engine_token_fetches_and_caches(): void
    {
        Http::fake([
            'booking.guesty.com/oauth2/token' => Http::response([
                'access_token' => 'test-booking-token',
                'token_type'   => 'Bearer',
            ], 200),
        ]);

        $client = new GuestyClient();
        $token = $client->getBookingEngineToken();

        $this->assertEquals('test-booking-token', $token);
    }

    public function test_open_api_get_makes_authenticated_request(): void
    {
        Http::fake([
            'open-api.guesty.com/oauth2/token' => Http::response([
                'access_token' => 'test-token',
            ], 200),
            'open-api.guesty.com/v1/listings*' => Http::response([
                'results' => [['_id' => '123', 'title' => 'Villa']],
                'count'   => 1,
            ], 200),
        ]);

        $client = new GuestyClient();
        $result = $client->openApiGet('listings', ['limit' => 10]);

        $this->assertEquals(200, $result['status']);
        $this->assertArrayHasKey('results', $result['data']);
    }

    public function test_open_api_post_sends_data(): void
    {
        Http::fake([
            'open-api.guesty.com/oauth2/token' => Http::response([
                'access_token' => 'test-token',
            ], 200),
            'open-api.guesty.com/v1/reservations' => Http::response([
                '_id' => 'res-123',
            ], 200),
        ]);

        $client = new GuestyClient();
        $result = $client->openApiPost('reservations', ['listingId' => 'listing-123']);

        $this->assertEquals(200, $result['status']);
    }

    public function test_booking_api_get_uses_booking_token(): void
    {
        Http::fake([
            'booking.guesty.com/oauth2/token' => Http::response([
                'access_token' => 'booking-token',
            ], 200),
            'booking.guesty.com/api/listings*' => Http::response([
                'data' => [],
            ], 200),
        ]);

        $client = new GuestyClient();
        $result = $client->bookingApiGet('api/listings/123/payment-provider');

        $this->assertEquals(200, $result['status']);
    }

    public function test_open_api_token_returns_empty_on_failure(): void
    {
        Http::fake([
            'open-api.guesty.com/oauth2/token' => Http::response('Unauthorized', 401),
        ]);

        $client = new GuestyClient();
        $token = $client->getOpenApiToken();

        $this->assertEquals('', $token);
    }
}
