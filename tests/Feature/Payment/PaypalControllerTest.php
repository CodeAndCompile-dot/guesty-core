<?php

namespace Tests\Feature\Payment;

use App\Models\BookingRequest;
use App\Models\Location;
use App\Models\Payment;
use App\Models\Property;
use App\Services\Payment\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaypalControllerTest extends TestCase
{
    use RefreshDatabase;

    private Property $property;

    private BookingRequest $booking;

    protected function setUp(): void
    {
        parent::setUp();

        $location = Location::create([
            'name'    => 'Test Location',
            'seo_url' => 'test-loc',
            'status'  => 1,
        ]);

        $this->property = Property::create([
            'name'        => 'Test Villa',
            'seo_url'     => 'test-villa',
            'location_id' => $location->id,
            'status'      => 1,
        ]);

        $this->booking = BookingRequest::create([
            'property_id'  => $this->property->id,
            'checkin'      => '2025-08-01',
            'checkout'     => '2025-08-05',
            'name'         => 'Jane Doe',
            'email'        => 'jane@example.com',
            'total_amount' => 800.00,
            'gross_amount' => 800.00,
            'amount_data'  => json_encode([
                ['amount' => '400.00'],
                ['amount' => '400.00'],
            ]),
        ]);
    }

    /* ------------------------------------------------------------------ */
    /*  index (GET paypal form)                                            */
    /* ------------------------------------------------------------------ */

    public function test_index_shows_paypal_form(): void
    {
        $response = $this->get(route('paypal', $this->booking->id));

        $response->assertStatus(200);
        $response->assertViewIs('front.booking.payment.paypal');
        $response->assertViewHas('booking');
        $response->assertViewHas('property');
    }

    public function test_index_returns_404_for_invalid_booking(): void
    {
        $response = $this->get(route('paypal', 99999));

        $response->assertStatus(404);
    }

    public function test_index_returns_404_when_property_missing(): void
    {
        $booking = BookingRequest::create([
            'property_id'  => 99999,
            'checkin'      => '2025-08-01',
            'checkout'     => '2025-08-05',
            'name'         => 'Bob',
            'email'        => 'bob@test.com',
            'total_amount' => 100,
        ]);

        $response = $this->get(route('paypal', $booking->id));
        $response->assertStatus(404);
    }

    public function test_index_redirects_to_stripe_when_gateway_is_stripe(): void
    {
        // Set up the basic_settings so which_payment_gateway = stripe
        \DB::table('basic_settings')->insert([
            'name'  => 'which_payment_gateway',
            'value' => 'stripe',
        ]);

        // Clear the cached settings
        \Illuminate\Support\Facades\Cache::flush();

        $response = $this->get(route('paypal', $this->booking->id));

        $response->assertRedirect(route('stripe_payment', $this->booking->id));
    }

    /* ------------------------------------------------------------------ */
    /*  verify (GET paypal callback)                                       */
    /* ------------------------------------------------------------------ */

    public function test_verify_success_redirects_to_receipt(): void
    {
        $mockService = $this->createMock(PaymentService::class);
        $mockService->expects($this->once())
            ->method('recordPaypal')
            ->willReturn([
                'success' => true,
                'payment' => Payment::create([
                    'booking_id' => $this->booking->id,
                    'tran_id'    => 'PAYPAL-TX',
                    'type'       => 'paypal',
                    'status'     => 'complete',
                    'amount'     => 400,
                ]),
                'message' => 'Payment successful',
            ]);

        $this->app->instance(PaymentService::class, $mockService);

        $response = $this->get(route('paypal.submit', $this->booking->id) . '?' . http_build_query([
            'tx'          => 'PAYPAL-TX',
            'st'          => 'COMPLETED',
            'amt'         => '400.00',
            'item_number' => $this->booking->id,
        ]));

        $response->assertRedirect('payment/success/' . $this->booking->id);
        $response->assertSessionHas('success');
    }

    public function test_verify_failure_redirects_back_with_error(): void
    {
        $mockService = $this->createMock(PaymentService::class);
        $mockService->expects($this->once())
            ->method('recordPaypal')
            ->willReturn([
                'success' => false,
                'payment' => null,
                'message' => 'Payment was not completed',
            ]);

        $this->app->instance(PaymentService::class, $mockService);

        $response = $this->get(route('paypal.submit', $this->booking->id) . '?' . http_build_query([
            'tx'  => 'PAYPAL-TX',
            'st'  => 'PENDING',
            'amt' => '400.00',
        ]));

        $response->assertRedirect();
        $response->assertSessionHas('danger', 'Payment was not completed');
    }
}
