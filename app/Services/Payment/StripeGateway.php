<?php

namespace App\Services\Payment;

use Stripe\Charge;
use Stripe\Customer;
use Stripe\PaymentIntent;
use Stripe\SetupIntent;
use Stripe\Stripe;
use Stripe\StripeClient;

/**
 * StripeGateway — thin wrapper around the Stripe PHP SDK.
 *
 * Reads keys from basic_settings (via ModelHelper facade) at runtime,
 * matching the legacy approach. All Stripe exceptions bubble up to the caller.
 */
class StripeGateway
{
    /**
     * Create a Stripe Charge using a client-side token.
     */
    public function createCharge(string $email, string $token, float $amount, string $description): Charge
    {
        Stripe::setApiKey($this->getSecretKey());

        $customer = Customer::create([
            'email'  => $email,
            'source' => $token,
        ]);

        return Charge::create([
            'customer'    => $customer->id,
            'amount'      => (int) round($amount * 100),
            'currency'    => 'usd',
            'description' => $description,
        ]);
    }

    /**
     * Create a SetupIntent for saving card details.
     */
    public function createSetupIntent(): SetupIntent
    {
        $client = new StripeClient($this->getSecretKey());

        return $client->setupIntents->create([
            'automatic_payment_methods' => ['enabled' => true],
        ]);
    }

    /**
     * Create a PaymentIntent for a given amount.
     */
    public function createPaymentIntent(float $amount): PaymentIntent
    {
        Stripe::setApiKey($this->getSecretKey());

        return PaymentIntent::create([
            'amount'               => (int) round($amount * 100),
            'currency'             => 'USD',
            'description'          => 'Website Payment',
            'payment_method_types' => ['card'],
        ]);
    }

    /**
     * Get the Stripe secret key from settings.
     */
    protected function getSecretKey(): string
    {
        return \ModelHelper::getDataFromSetting('stripe_secret_key') ?? '';
    }
}
