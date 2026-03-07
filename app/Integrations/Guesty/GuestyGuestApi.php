<?php

namespace App\Integrations\Guesty;

use App\Integrations\Guesty\Contracts\GuestyClientInterface;

class GuestyGuestApi
{
    public function __construct(
        protected GuestyClientInterface $client,
    ) {}

    /**
     * Create a guest in Guesty.
     */
    public function createGuest(string $firstName, string $lastName, string $email, string $mobile): array
    {
        return $this->client->openApiPost('guests-crud', [
            'firstName' => $firstName,
            'lastName'  => $lastName,
            'email'     => $email,
            'phone'     => $mobile,
        ]);
    }

    /**
     * Retrieve a guest by ID.
     */
    public function getGuest(string $guestId): array
    {
        return $this->client->openApiGet("guests-crud/{$guestId}");
    }
}
