<?php

namespace Tests\Feature\Payment;

use App\Models\BasicSetting;
use App\Models\BookingRequest;
use App\Models\GuestyProperty;
use App\Models\Location;
use App\Models\Payment;
use App\Models\Property;
use App\Services\Payment\PaymentService;
use App\Services\Payment\StripeGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StripeControllerTest extends TestCase
{
    use RefreshDatabase;

    private Property $property;

    private GuestyProperty $guestyProperty;

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

        $this->guestyProperty = GuestyProperty::create([
            'id'    => $this->property->id,
            'title' => 'Guesty Villa',
        ]);

        $this->booking = BookingRequest::create([
            'property_id'  => $this->property->id,
            'checkin'      => '2025-08-01',
            'checkout'     => '2025-08-05',
            'name'         => 'John Doe',
            'email'        => 'john@example.com',
            'total_amount' => 1000.00,
            'gross_amount' => 900.00,
            'total_night'  => 4,
            'total_guests' => 2,
            'amount_data'  => json_encode([
                ['amount' => '500.00'],
                ['amount' => '500.00'],
            ]),
        ]);
    }

    /* ------------------------------------------------------------------ */
    /*  index (GET stripe payment form)                                    */
    /* ------------------------------------------------------------------ */

    public function test_index_shows_stripe_form(): void
    {
        $response = $this->get(route('stripe_payment', $this->booking->id));

        $response->assertStatus(200);
        $response->assertViewIs('front.booking.payment.stripe');
        $response->assertViewHas('booking');
        $response->assertViewHas('property');
    }

    public function test_index_returns_404_for_invalid_booking(): void
    {
        $response = $this->get(route('stripe_payment', 99999));

        $response->assertStatus(404);
    }

    public function test_index_returns_404_when_guesty_property_missing(): void
    {
        $this->guestyProperty->delete();

        // Also need to make Property not found by id. Since index uses GuestyProperty::find, and
        // our property has same ID, we need to test without guesty_property matching.
        // Create a booking referencing a non-existent property
        $booking = BookingRequest::create([
            'property_id'  => 99999,
            'checkin'      => '2025-08-01',
            'checkout'     => '2025-08-05',
            'name'         => 'Bob',
            'email'        => 'bob@test.com',
            'total_amount' => 100,
        ]);

        $response = $this->get(route('stripe_payment', $booking->id));
        $response->assertStatus(404);
    }

    /* ------------------------------------------------------------------ */
    /*  store (POST charge)                                                */
    /* ------------------------------------------------------------------ */

    public function test_store_success_redirects_to_receipt(): void
    {
        $mockService = $this->createMock(PaymentService::class);
        $mockService->expects($this->once())
            ->method('chargeStripe')
            ->willReturn([
                'success' => true,
                'payment' => Payment::create([
                    'booking_id' => $this->booking->id,
                    'tran_id'    => 'ch_test',
                    'type'       => 'stripe',
                    'status'     => 'complete',
                    'amount'     => 500,
                ]),
                'message' => 'Payment successful',
            ]);

        $this->app->instance(PaymentService::class, $mockService);

        $response = $this->post(route('stripe.post', $this->booking->id), [
            'stripeToken' => 'tok_test123',
            'amount'      => 500.00,
        ]);

        $response->assertRedirect('payment/success/' . $this->booking->id);
        $response->assertSessionHas('success');
    }

    public function test_store_failure_redirects_back_with_error(): void
    {
        $mockService = $this->createMock(PaymentService::class);
        $mockService->expects($this->once())
            ->method('chargeStripe')
            ->willReturn([
                'success' => false,
                'payment' => null,
                'message' => 'Card declined',
            ]);

        $this->app->instance(PaymentService::class, $mockService);

        $response = $this->post(route('stripe.post', $this->booking->id), [
            'stripeToken' => 'tok_test123',
            'amount'      => 500.00,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('danger', 'Card declined');
    }

    public function test_store_validation_rejects_missing_token(): void
    {
        $response = $this->post(route('stripe.post', $this->booking->id), [
            'amount' => 500.00,
        ]);

        $response->assertSessionHasErrors('stripeToken');
    }

    public function test_store_validation_rejects_low_amount(): void
    {
        $response = $this->post(route('stripe.post', $this->booking->id), [
            'stripeToken' => 'tok_test',
            'amount'      => 0.10,
        ]);

        $response->assertSessionHasErrors('amount');
    }

    /* ------------------------------------------------------------------ */
    /*  getIntentData (JSON)                                               */
    /* ------------------------------------------------------------------ */

    public function test_get_intent_data_returns_json(): void
    {
        $mockService = $this->createMock(PaymentService::class);
        $mockService->expects($this->once())
            ->method('createSetupIntent')
            ->willReturn(['status' => 200, 'message' => 'success', 'stripe_object' => 'seti_test']);

        $this->app->instance(PaymentService::class, $mockService);

        $response = $this->getJson('/getIntendentData');

        $response->assertOk();
        $response->assertJson(['status' => 200]);
    }

    /* ------------------------------------------------------------------ */
    /*  paymentInit (JSON)                                                */
    /* ------------------------------------------------------------------ */

    public function test_payment_init_returns_json(): void
    {
        $mockService = $this->createMock(PaymentService::class);
        $mockService->expects($this->once())
            ->method('createPaymentIntent')
            ->willReturn(['id' => 'pi_test', 'clientSecret' => 'pi_sec']);

        $this->app->instance(PaymentService::class, $mockService);

        $response = $this->postJson(route('payment_init'), [
            'total_amount' => 100.00,
        ]);

        $response->assertOk();
        $response->assertJson(['id' => 'pi_test']);
    }
}
