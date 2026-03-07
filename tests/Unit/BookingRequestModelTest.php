<?php

namespace Tests\Unit;

use App\Models\BookingRequest;
use App\Models\Location;
use App\Models\Payment;
use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingRequestModelTest extends TestCase
{
    use RefreshDatabase;

    private Property $property;

    protected function setUp(): void
    {
        parent::setUp();

        $location = Location::create([
            'name' => 'ML', 'seo_url' => 'ml', 'parent_id' => null, 'status' => 1,
        ]);

        $this->property = Property::create([
            'name' => 'MP', 'seo_url' => 'mp', 'location_id' => $location->id, 'status' => 1,
        ]);
    }

    public function test_booking_belongs_to_property(): void
    {
        $booking = BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin'      => '2025-08-01',
            'checkout'     => '2025-08-05',
            'name'         => 'Rel Test',
            'email'        => 'rel@example.com',
        ]);

        $this->assertInstanceOf(Property::class, $booking->property);
        $this->assertEquals($this->property->id, $booking->property->id);
    }

    public function test_booking_has_many_payments(): void
    {
        $booking = BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin'      => '2025-08-01',
            'checkout'     => '2025-08-05',
            'name'         => 'Pay Rel',
            'email'        => 'payrel@example.com',
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'tran_id'    => 'txn_1',
            'amount'     => '100.00',
        ]);
        Payment::create([
            'booking_id' => $booking->id,
            'tran_id'    => 'txn_2',
            'amount'     => '200.00',
        ]);

        $this->assertCount(2, $booking->payments);
    }

    public function test_payment_belongs_to_booking_request(): void
    {
        $booking = BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin'      => '2025-08-01',
            'checkout'     => '2025-08-05',
            'name'         => 'Rev Rel',
            'email'        => 'revrel@example.com',
        ]);

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'tran_id'    => 'txn_rev',
            'amount'     => '50.00',
        ]);

        $this->assertInstanceOf(BookingRequest::class, $payment->bookingRequest);
        $this->assertEquals($booking->id, $payment->bookingRequest->id);
    }

    public function test_booking_fillable_includes_all_expected_fields(): void
    {
        $booking = new BookingRequest();
        $fillable = $booking->getFillable();

        $required = [
            'property_id', 'checkin', 'checkout', 'name', 'email',
            'booking_status', 'booking_type_admin', 'payment_status',
            'total_amount', 'adults', 'child', 'mobile', 'message',
            'before_total_fees', 'after_total_fees', 'amount_data',
        ];

        foreach ($required as $field) {
            $this->assertContains($field, $fillable, "Missing fillable field: {$field}");
        }
    }

    public function test_booking_default_values(): void
    {
        $booking = BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin'      => '2025-08-01',
            'checkout'     => '2025-08-05',
            'name'         => 'Defaults',
            'email'        => 'defaults@example.com',
        ]);

        $booking->refresh();

        $this->assertEquals('booked', $booking->booking_status);
        $this->assertEquals('pending', $booking->payment_status);
        $this->assertEquals('invoice', $booking->booking_type_admin);
        $this->assertEquals('red', $booking->color);
        $this->assertEquals('false', $booking->rental_aggrement_status);
        $this->assertEquals(1, $booking->total_payment);
        $this->assertEquals(0, $booking->how_many_payment_done);
    }

    public function test_payment_default_values(): void
    {
        $booking = BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin'      => '2025-08-01',
            'checkout'     => '2025-08-05',
            'name'         => 'PayDef',
            'email'        => 'paydef@example.com',
        ]);

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'tran_id'    => 'txn_def',
            'amount'     => '75.00',
        ]);

        $payment->refresh();
        $this->assertEquals('pending', $payment->status);
        $this->assertEquals('stripe', $payment->type);
    }
}
