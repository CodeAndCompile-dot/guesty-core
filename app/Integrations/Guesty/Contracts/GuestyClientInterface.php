<?php

namespace App\Integrations\Guesty\Contracts;

interface GuestyClientInterface
{
    /**
     * Get a valid Open API access token (cached).
     */
    public function getOpenApiToken(): string;

    /**
     * Get a valid Booking Engine API access token (cached).
     */
    public function getBookingEngineToken(): string;

    /**
     * Make an authenticated GET request to the Open API.
     */
    public function openApiGet(string $endpoint, array $query = []): array;

    /**
     * Make an authenticated POST request to the Open API.
     */
    public function openApiPost(string $endpoint, array $data = []): array;

    /**
     * Make an authenticated PUT request to the Open API.
     */
    public function openApiPut(string $endpoint, array $data = []): array;

    /**
     * Make an authenticated GET request to the Booking Engine API.
     */
    public function bookingApiGet(string $endpoint, array $query = []): array;

    /**
     * Make an authenticated POST request to the Booking Engine API.
     */
    public function bookingApiPost(string $endpoint, array $data = []): array;
}
