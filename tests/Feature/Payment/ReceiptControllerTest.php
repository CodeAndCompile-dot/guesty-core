<?php

namespace Tests\Feature\Payment;

use App\Models\BookingRequest;
use App\Models\GuestyProperty;
use App\Models\Location;
use App\Models\Property;
use App\Services\Payment\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReceiptControllerTest extends TestCase
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
            'checkin'      => '2025-09-01',
            'checkout'     => '2025-09-05',
            'name'         => 'Alice Smith',
            'firstname'    => 'Alice',
            'lastname'     => 'Smith',
            'email'        => 'alice@example.com',
            'total_amount' => 1200.00,
            'gross_amount' => 1100.00,
            'total_night'  => 4,
            'total_guests' => 3,
        ]);
    }

    public function test_show_displays_receipt(): void
    {
        $response = $this->get('/payment/success/' . $this->booking->id);

        $response->assertStatus(200);
        $response->assertViewIs('front.booking.payment.first-preview');
        $response->assertViewHas('booking');
        $response->assertViewHas('property');
    }

    public function test_show_returns_404_for_invalid_booking(): void
    {
        $response = $this->get('/payment/success/99999');

        $response->assertStatus(404);
    }

    public function test_show_returns_404_when_property_missing(): void
    {
        $booking = BookingRequest::create([
            'property_id'  => 99999,
            'checkin'      => '2025-09-01',
            'checkout'     => '2025-09-05',
            'name'         => 'Bob',
            'email'        => 'bob@test.com',
            'total_amount' => 100,
        ]);

        $response = $this->get('/payment/success/' . $booking->id);
        $response->assertStatus(404);
    }
}
