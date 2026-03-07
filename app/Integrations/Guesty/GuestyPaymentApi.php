<?php

namespace App\Integrations\Guesty;

use App\Integrations\Guesty\Contracts\GuestyClientInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GuestyPaymentApi
{
    public function __construct(
        protected GuestyClientInterface $client,
    ) {}

    /**
     * Attach a payment method to a guest's reservation.
     */
    public function attachPaymentMethod(
        string $guestId,
        string $paymentProviderId,
        string $paymentMethodToken,
        string $reservationId,
    ): array {
        return $this->client->openApiPost("guests/{$guestId}/payment-methods", [
            'paymentProviderId'  => $paymentProviderId,
            'paymentMethodToken' => $paymentMethodToken,
            'reservationId'      => $reservationId,
        ]);
    }

    /**
     * Record a payment against a reservation.
     */
    public function recordPayment(string $reservationId, float $amount, string $stripePaymentId): array
    {
        return $this->client->openApiPost("reservations/{$reservationId}/payments", [
            'amount'            => $amount,
            'paymentMethodId'   => $stripePaymentId,
            'shouldBePaidAt'    => now()->format('Y-m-d'),
        ]);
    }

    /**
     * Get the payment provider for a listing from the Booking Engine API.
     */
    public function getListingPaymentProvider(string $listingId): array
    {
        return $this->client->bookingApiGet("api/listings/{$listingId}/payment-provider");
    }

    /**
     * Get payment providers summary from the Open API.
     */
    public function getPaymentProvidersSummary(): array
    {
        return $this->client->openApiGet('payment-providers/summary');
    }

    /**
     * Tokenize a card via the Guesty Pay API (pay.guesty.com).
     * This call is separate from the standard Open/Booking APIs.
     */
    public function tokenizeCard(
        string $payUrl,
        string $providerId,
        string $cardNumber,
        string $expMonth,
        string $expYear,
        string $cvv,
    ): array {
        try {
            $response = Http::post("{$payUrl}/tokenize", [
                'providerId' => $providerId,
                'card'       => [
                    'number'   => $cardNumber,
                    'expMonth' => $expMonth,
                    'expYear'  => $expYear,
                    'cvc'      => $cvv,
                ],
            ]);

            if ($response->successful()) {
                return ['status' => 200, 'data' => $response->json()];
            }

            return [
                'status'  => $response->status(),
                'message' => $response->body(),
            ];
        } catch (\Throwable $e) {
            Log::error('Guesty Pay tokenize failed', ['error' => $e->getMessage()]);

            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }
}
