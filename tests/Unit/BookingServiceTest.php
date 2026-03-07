<?php

namespace Tests\Unit;

use App\Models\BookingRequest;
use App\Models\Location;
use App\Models\Property;
use App\Services\BookingService;
use App\Services\Calendar\ICalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingServiceTest extends TestCase
{
    use RefreshDatabase;

    private BookingService $service;

    private Property $property;

    protected function setUp(): void
    {
        parent::setUp();

        $location = Location::create([
            'name' => 'Loc', 'seo_url' => 'loc', 'parent_id' => null, 'status' => 1,
        ]);

        $this->property = Property::create([
            'name' => 'Prop', 'seo_url' => 'prop', 'location_id' => $location->id, 'status' => 1,
        ]);

        $this->service = app(BookingService::class);
    }

    // ------------------------------------------------------------------
    // listAll
    // ------------------------------------------------------------------

    public function test_list_all_returns_all_bookings_desc(): void
    {
        BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin' => '2025-08-01', 'checkout' => '2025-08-05',
            'name' => 'First', 'email' => 'first@example.com',
        ]);
        BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin' => '2025-09-01', 'checkout' => '2025-09-05',
            'name' => 'Second', 'email' => 'second@example.com',
        ]);

        $result = $this->service->listAll();

        $this->assertCount(2, $result);
        $this->assertEquals('Second', $result->first()->name);
    }

    // ------------------------------------------------------------------
    // listForProperty
    // ------------------------------------------------------------------

    public function test_list_for_property_returns_bookings(): void
    {
        BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin' => '2025-08-01', 'checkout' => '2025-08-05',
            'name' => 'Prop Booking', 'email' => 'pb@example.com',
        ]);

        $result = $this->service->listForProperty($this->property->id);

        $this->assertCount(1, $result);
        $this->assertEquals('Prop Booking', $result->first()->name);
    }

    public function test_list_for_property_returns_null_for_missing(): void
    {
        $result = $this->service->listForProperty(99999);
        $this->assertNull($result);
    }

    // ------------------------------------------------------------------
    // store
    // ------------------------------------------------------------------

    public function test_store_invoice_keeps_default_status(): void
    {
        $booking = $this->service->store([
            'booking_type_admin' => 'invoice',
            'property_id'       => $this->property->id,
            'checkin'            => '2025-09-01',
            'checkout'           => '2025-09-05',
            'name'               => 'Invoice Guest',
            'email'              => 'inv@example.com',
        ]);

        $this->assertInstanceOf(BookingRequest::class, $booking);
        $booking->refresh();
        $this->assertEquals('booked', $booking->booking_status);
    }

    public function test_store_manual_sets_booking_confirmed(): void
    {
        $booking = $this->service->store([
            'booking_type_admin' => 'manual',
            'property_id'       => $this->property->id,
            'checkin'            => '2025-09-01',
            'checkout'           => '2025-09-05',
            'name'               => 'Manual Guest',
            'email'              => 'man@example.com',
        ]);

        $this->assertEquals('booking-confirmed', $booking->booking_status);
    }

    // ------------------------------------------------------------------
    // update
    // ------------------------------------------------------------------

    public function test_update_modifies_existing_booking(): void
    {
        $booking = BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin' => '2025-08-01', 'checkout' => '2025-08-05',
            'name' => 'Old', 'email' => 'old@example.com',
        ]);

        $result = $this->service->update($booking->id, [
            'booking_type_admin' => 'invoice',
            'property_id'       => $this->property->id,
            'checkin'            => '2025-08-02',
            'checkout'           => '2025-08-06',
            'name'               => 'Updated',
            'email'              => 'new@example.com',
        ]);

        $this->assertNotNull($result);
        $this->assertEquals('Updated', $result->name);
        $this->assertEquals('2025-08-02', $result->checkin);
    }

    public function test_update_returns_null_for_missing(): void
    {
        $result = $this->service->update(99999, [
            'booking_type_admin' => 'invoice',
            'property_id'       => $this->property->id,
            'checkin'            => '2025-08-02',
            'checkout'           => '2025-08-06',
            'name'               => 'Ghost',
            'email'              => 'ghost@example.com',
        ]);

        $this->assertNull($result);
    }

    public function test_update_manual_sets_booking_confirmed(): void
    {
        $booking = BookingRequest::create([
            'property_id' => $this->property->id,
            'booking_type_admin' => 'invoice',
            'booking_status' => 'booked',
            'checkin' => '2025-08-01', 'checkout' => '2025-08-05',
            'name' => 'Switch', 'email' => 'switch@example.com',
        ]);

        $result = $this->service->update($booking->id, [
            'booking_type_admin' => 'manual',
            'property_id'       => $this->property->id,
            'checkin'            => '2025-08-01',
            'checkout'           => '2025-08-05',
            'name'               => 'Switch',
            'email'              => 'switch@example.com',
        ]);

        $this->assertEquals('booking-confirmed', $result->booking_status);
    }

    // ------------------------------------------------------------------
    // cancel
    // ------------------------------------------------------------------

    public function test_cancel_sets_booking_cancel_status(): void
    {
        $booking = BookingRequest::create([
            'property_id' => $this->property->id,
            'booking_type_admin' => 'invoice',
            'booking_status' => 'booked',
            'checkin' => '2025-08-01', 'checkout' => '2025-08-05',
            'name' => 'Cancel Me', 'email' => 'cancel@example.com',
        ]);

        $result = $this->service->cancel($booking->id);

        $this->assertTrue($result['success']);
        $this->assertEquals($this->property->id, $result['property_id']);

        $booking->refresh();
        $this->assertEquals('booking-cancel', $booking->booking_status);
    }

    public function test_cancel_returns_failure_for_missing(): void
    {
        $result = $this->service->cancel(99999);
        $this->assertFalse($result['success']);
    }

    // ------------------------------------------------------------------
    // confirm
    // ------------------------------------------------------------------

    public function test_confirm_sets_rental_aggrement_status(): void
    {
        $booking = BookingRequest::create([
            'property_id' => $this->property->id,
            'booking_status' => 'booked',
            'checkin' => '2025-08-01', 'checkout' => '2025-08-05',
            'name' => 'Confirm Me', 'email' => 'confirm@example.com',
        ]);

        $result = $this->service->confirm($booking->id);

        $this->assertTrue($result['success']);
        $this->assertEquals($this->property->id, $result['property_id']);

        $booking->refresh();
        $this->assertEquals('rental-aggrement', $booking->booking_status);
    }

    public function test_confirm_returns_failure_for_missing_booking(): void
    {
        $result = $this->service->confirm(99999);
        $this->assertFalse($result['success']);
    }

    public function test_confirm_returns_failure_for_missing_property(): void
    {
        $booking = BookingRequest::create([
            'property_id' => 99999, // non-existent property
            'booking_status' => 'booked',
            'checkin' => '2025-08-01', 'checkout' => '2025-08-05',
            'name' => 'No Prop', 'email' => 'noprop@example.com',
        ]);

        $result = $this->service->confirm($booking->id);
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Property not found', $result['message']);
    }

    // ------------------------------------------------------------------
    // find
    // ------------------------------------------------------------------

    public function test_find_returns_booking(): void
    {
        $booking = BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin' => '2025-08-01', 'checkout' => '2025-08-05',
            'name' => 'Find Me', 'email' => 'find@example.com',
        ]);

        $result = $this->service->find($booking->id);
        $this->assertNotNull($result);
        $this->assertEquals('Find Me', $result->name);
    }

    public function test_find_returns_null_for_missing(): void
    {
        $this->assertNull($this->service->find(99999));
    }
}
