<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class IntegrationServiceProvider extends ServiceProvider
{
    /**
     * All integration interface => implementation bindings.
     * Add new bindings here as integrations are built in later phases.
     *
     * @var array<class-string, class-string>
     */
    protected array $integrations = [
        // Phase 5: Guesty PMS Integration
        \App\Integrations\Guesty\Contracts\GuestyClientInterface::class => \App\Integrations\Guesty\GuestyClient::class,
        // Phase 6+: Uncomment as integrations are created
        // \App\Integrations\PriceLabs\Contracts\PriceLabsClientInterface::class => \App\Integrations\PriceLabs\PriceLabsClient::class,
        // \App\Integrations\Stripe\Contracts\StripeGatewayInterface::class => \App\Integrations\Stripe\StripeGateway::class,
        // \App\Integrations\PayPal\Contracts\PayPalGatewayInterface::class => \App\Integrations\PayPal\PayPalGateway::class,
        // \App\Integrations\ICal\Contracts\ICalParserInterface::class => \App\Integrations\ICal\ICalParser::class,
        // \App\Integrations\ICal\Contracts\ICalExporterInterface::class => \App\Integrations\ICal\ICalExporter::class,
    ];

    public function register(): void
    {
        foreach ($this->integrations as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }
}
