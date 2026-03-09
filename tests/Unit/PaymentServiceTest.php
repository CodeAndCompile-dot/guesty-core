<?php

namespace Tests\Unit;

use App\Models\BookingRequest;
use App\Models\GuestyProperty;
use App\Models\IcalImportList;
use App\Models\Location;
use App\Models\Payment;
use App\Models\Property;
use App\Services\Calendar\ICalService;
use App\Services\Communication\EmailService;
use App\Services\Payment\PaymentService;
use App\Services\Payment\StripeGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    private PaymentService $service;

    private StripeGateway $stripeGatewayMock;

    private ICalService $icalServiceMock;

    private EmailService $emailServiceMock;

    private Property $property;

    private BookingRequest $booking;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stripeGatewayMock = $this->createMock(StripeGateway::class);
        $this->icalServiceMock   = $this->createMock(ICalService::class);
        $this->emailServiceMock  = $this->createMock(EmailService::class);

        $this->service = new PaymentService(
            $this->stripeGatewayMock,
            $this->icalServiceMock,
            $this->emailServiceMock,
        );

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
            'total_payment' => 2,
        ]);
    }

    /* ------------------------------------------------------------------ */
    /*  chargeStripe                                                       */
    /* ------------------------------------------------------------------ */

    public function test_charge_stripe_returns_error_for_invalid_booking(): void
    {
        $result = $this->service->chargeStripe(99999, 'tok_test', 100.00);

        $this->assertFalse($result['success']);
        $this->assertNull($result['payment']);
        $this->assertEquals('Booking is invalid', $result['message']);
    }

    public function test_charge_stripe_returns_error_when_property_missing(): void
    {
        // Delete the property so lookup fails
        $this->property->delete();

        $result = $this->service->chargeStripe($this->booking->id, 'tok_test', 500.00);

        $this->assertFalse($result['success']);
        $this->assertEquals('Property is no longer available', $result['message']);
    }

    public function test_charge_stripe_success_creates_payment_and_updates_booking(): void
    {
        $fakeCharge = \Stripe\Charge::constructFrom([
            'amount_refunded'     => 0,
            'failure_code'        => null,
            'paid'                => 1,
            'captured'            => 1,
            'receipt_url'         => 'https://stripe.com/receipt/abc',
            'customer'            => 'cus_test123',
            'balance_transaction' => 'txn_test123',
            'id'                  => 'ch_test123',
        ]);

        $this->stripeGatewayMock
            ->expects($this->once())
            ->method('createCharge')
            ->willReturn($fakeCharge);

        $result = $this->service->chargeStripe($this->booking->id, 'tok_test', 500.00);

        $this->assertTrue($result['success']);
        $this->assertInstanceOf(Payment::class, $result['payment']);
        $this->assertEquals('Payment successful', $result['message']);

        // Payment record created
        $this->assertDatabaseHas('payments', [
            'booking_id' => $this->booking->id,
            'tran_id'    => 'ch_test123',
            'type'       => 'stripe',
            'status'     => 'complete',
            'amount'     => 500.00,
        ]);

        // Booking updated
        $this->booking->refresh();
        $this->assertEquals('booking-confirmed', $this->booking->booking_status);
        $this->assertEquals('partially', $this->booking->payment_status);
        $this->assertEquals(1, $this->booking->how_many_payment_done);
    }

    public function test_charge_stripe_handles_stripe_exception(): void
    {
        $this->stripeGatewayMock
            ->expects($this->once())
            ->method('createCharge')
            ->willThrowException(new \RuntimeException('Card declined'));

        $result = $this->service->chargeStripe($this->booking->id, 'tok_test', 500.00);

        $this->assertFalse($result['success']);
        $this->assertNull($result['payment']);
        $this->assertEquals('Card declined', $result['message']);
    }

    public function test_charge_stripe_returns_not_captured_when_charge_fails(): void
    {
        $fakeCharge = \Stripe\Charge::constructFrom([
            'amount_refunded' => 0,
            'failure_code'    => 'card_declined',
            'paid'            => 0,
            'captured'        => 0,
        ]);

        $this->stripeGatewayMock
            ->expects($this->once())
            ->method('createCharge')
            ->willReturn($fakeCharge);

        $result = $this->service->chargeStripe($this->booking->id, 'tok_test', 500.00);

        $this->assertFalse($result['success']);
        $this->assertEquals('Payment was not captured', $result['message']);
    }

    /* ------------------------------------------------------------------ */
    /*  recordPaypal                                                       */
    /* ------------------------------------------------------------------ */

    public function test_record_paypal_success(): void
    {
        $result = $this->service->recordPaypal(
            $this->booking->id,
            'PAYPAL-TX-123',
            'COMPLETED',
            500.00,
        );

        $this->assertTrue($result['success']);
        $this->assertInstanceOf(Payment::class, $result['payment']);

        $this->assertDatabaseHas('payments', [
            'booking_id' => $this->booking->id,
            'tran_id'    => 'PAYPAL-TX-123',
            'type'       => 'paypal',
            'status'     => 'complete',
            'amount'     => 500.00,
        ]);

        $this->booking->refresh();
        $this->assertEquals('booking-confirmed', $this->booking->booking_status);
    }

    public function test_record_paypal_invalid_booking(): void
    {
        $result = $this->service->recordPaypal(99999, 'TX1', 'COMPLETED', 100);

        $this->assertFalse($result['success']);
        $this->assertEquals('Booking is invalid', $result['message']);
    }

    public function test_record_paypal_property_missing(): void
    {
        $this->property->delete();

        $result = $this->service->recordPaypal($this->booking->id, 'TX1', 'COMPLETED', 100);

        $this->assertFalse($result['success']);
        $this->assertEquals('Property is no longer available', $result['message']);
    }

    public function test_record_paypal_rejects_non_completed_status(): void
    {
        $result = $this->service->recordPaypal($this->booking->id, 'TX1', 'PENDING', 500);

        $this->assertFalse($result['success']);
        $this->assertEquals('Payment was not completed', $result['message']);
    }

    /* ------------------------------------------------------------------ */
    /*  resolveAmount                                                      */
    /* ------------------------------------------------------------------ */

    public function test_resolve_amount_without_discount(): void
    {
        $amount = $this->service->resolveAmount($this->booking);

        $this->assertEquals(1000.00, $amount);
    }

    public function test_resolve_amount_with_discount(): void
    {
        $this->booking->update([
            'discount'             => 10,
            'after_discount_total' => 900.00,
        ]);

        $amount = $this->service->resolveAmount($this->booking->refresh());

        $this->assertEquals(900.00, $amount);
    }

    public function test_resolve_amount_from_array(): void
    {
        $amount = $this->service->resolveAmount([
            'total_amount'         => 500.00,
            'discount'             => 20,
            'after_discount_total' => 400.00,
        ]);

        $this->assertEquals(400.00, $amount);
    }

    /* ------------------------------------------------------------------ */
    /*  findForReceipt                                                     */
    /* ------------------------------------------------------------------ */

    public function test_find_for_receipt_returns_booking_and_property(): void
    {
        $result = $this->service->findForReceipt($this->booking->id);

        $this->assertNotNull($result);
        $this->assertArrayHasKey('booking', $result);
        $this->assertArrayHasKey('property', $result);
        $this->assertEquals($this->booking->id, $result['booking']->id);
    }

    public function test_find_for_receipt_returns_null_for_invalid_booking(): void
    {
        $this->assertNull($this->service->findForReceipt(99999));
    }

    public function test_find_for_receipt_returns_null_when_property_missing(): void
    {
        $this->property->delete();

        $this->assertNull($this->service->findForReceipt($this->booking->id));
    }

    /* ------------------------------------------------------------------ */
    /*  findForReceipt prefers GuestyProperty                              */
    /* ------------------------------------------------------------------ */

    public function test_find_for_receipt_prefers_guesty_property(): void
    {
        $guestyProp = GuestyProperty::create([
            'id'    => $this->property->id,
            'title' => 'Guesty Villa',
        ]);

        $result = $this->service->findForReceipt($this->booking->id);

        $this->assertNotNull($result);
        $this->assertInstanceOf(GuestyProperty::class, $result['property']);
        $this->assertEquals('Guesty Villa', $result['property']->title);
    }

    /* ------------------------------------------------------------------ */
    /*  finalisePayment — instalment tracking                              */
    /* ------------------------------------------------------------------ */

    public function test_finalise_payment_marks_first_matching_instalment(): void
    {
        $payment = Payment::create([
            'booking_id' => $this->booking->id,
            'tran_id'    => 'ch_first',
            'type'       => 'stripe',
            'status'     => 'complete',
            'amount'     => 500.00,
        ]);

        $this->service->finalisePayment(
            $this->booking->id,
            $this->booking,
            $payment,
            $this->property,
        );

        $this->booking->refresh();
        $amountData = json_decode($this->booking->amount_data, true);

        $this->assertEquals('complete', $amountData[0]['status']);
        $this->assertEquals('ch_first', $amountData[0]['tran_id']);
        $this->assertArrayNotHasKey('status', $amountData[1]);
        $this->assertEquals('partially', $this->booking->payment_status);
        $this->assertEquals(1, $this->booking->how_many_payment_done);
    }

    public function test_finalise_payment_marks_fully_paid_when_all_instalments_complete(): void
    {
        // Pre-mark first instalment as complete
        $this->booking->update([
            'amount_data' => json_encode([
                ['amount' => '500.00', 'status' => 'complete', 'tran_id' => 'ch_first', 'mode' => 'stripe', 'date' => '2025-01-01'],
                ['amount' => '500.00'],
            ]),
            'how_many_payment_done' => 1,
        ]);

        $payment = Payment::create([
            'booking_id' => $this->booking->id,
            'tran_id'    => 'ch_second',
            'type'       => 'stripe',
            'status'     => 'complete',
            'amount'     => 500.00,
        ]);

        $this->service->finalisePayment(
            $this->booking->id,
            $this->booking->refresh(),
            $payment,
            $this->property,
        );

        $this->booking->refresh();
        $amountData = json_decode($this->booking->amount_data, true);

        $this->assertEquals('complete', $amountData[1]['status']);
        $this->assertEquals('ch_second', $amountData[1]['tran_id']);
        $this->assertEquals('paid', $this->booking->payment_status);
        $this->assertEquals(2, $this->booking->how_many_payment_done);
    }

    public function test_finalise_payment_refreshes_ical_imports(): void
    {
        IcalImportList::create([
            'property_id' => $this->property->id,
            'ical_link'   => 'https://example.com/cal.ics',
        ]);

        $this->icalServiceMock
            ->expects($this->once())
            ->method('refreshImport');

        $payment = Payment::create([
            'booking_id' => $this->booking->id,
            'tran_id'    => 'ch_ical',
            'type'       => 'stripe',
            'status'     => 'complete',
            'amount'     => 500.00,
        ]);

        $this->service->finalisePayment(
            $this->booking->id,
            $this->booking,
            $payment,
            $this->property,
        );
    }

    /* ------------------------------------------------------------------ */
    /*  createSetupIntent / createPaymentIntent                           */
    /* ------------------------------------------------------------------ */

    public function test_create_setup_intent_success(): void
    {
        $fakeIntent = \Stripe\SetupIntent::constructFrom(['id' => 'seti_test', 'client_secret' => 'seti_secret']);

        $this->stripeGatewayMock
            ->expects($this->once())
            ->method('createSetupIntent')
            ->willReturn($fakeIntent);

        $result = $this->service->createSetupIntent();

        $this->assertEquals(200, $result['status']);
        $this->assertEquals('success', $result['message']);
    }

    public function test_create_setup_intent_handles_failure(): void
    {
        $this->stripeGatewayMock
            ->expects($this->once())
            ->method('createSetupIntent')
            ->willThrowException(new \RuntimeException('No key'));

        $result = $this->service->createSetupIntent();

        $this->assertEquals(400, $result['status']);
        $this->assertEquals('No key', $result['message']);
    }

    public function test_create_payment_intent_success(): void
    {
        $fakeIntent = \Stripe\PaymentIntent::constructFrom(['id' => 'pi_test', 'client_secret' => 'pi_secret']);

        $this->stripeGatewayMock
            ->expects($this->once())
            ->method('createPaymentIntent')
            ->willReturn($fakeIntent);

        $result = $this->service->createPaymentIntent(100.00);

        $this->assertEquals('pi_test', $result['id']);
        $this->assertEquals('pi_secret', $result['clientSecret']);
    }

    public function test_create_payment_intent_handles_failure(): void
    {
        $this->stripeGatewayMock
            ->expects($this->once())
            ->method('createPaymentIntent')
            ->willThrowException(new \RuntimeException('Bad amount'));

        $result = $this->service->createPaymentIntent(0);

        $this->assertArrayHasKey('error', $result);
        $this->assertEquals('Bad amount', $result['error']);
    }
}
