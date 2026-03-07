<?php

namespace Tests\Feature\Admin;

use App\Models\BookingRequest;
use App\Models\Location;
use App\Models\Payment;
use App\Models\Property;
use App\Models\User;
use App\Services\Calendar\ICalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingRequestControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Property $property;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        $location = Location::create([
            'name'      => 'Test Location',
            'seo_url'   => 'test-location',
            'parent_id' => null,
            'status'    => 1,
        ]);

        $this->property = Property::create([
            'name'        => 'Test Property',
            'seo_url'     => 'test-property',
            'location_id' => $location->id,
            'status'      => 1,
        ]);
    }

    // ------------------------------------------------------------------
    // index
    // ------------------------------------------------------------------

    public function test_index_displays_booking_enquiries(): void
    {
        BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin'      => '2025-08-01',
            'checkout'     => '2025-08-05',
            'name'         => 'Jane Doe',
            'email'        => 'jane@example.com',
        ]);

        $response = $this->actingAs($this->user)->get(route('booking-enquiries.index'));

        $response->assertStatus(200);
        $response->assertViewHas('data');
    }

    public function test_index_requires_authentication(): void
    {
        $response = $this->get(route('booking-enquiries.index'));
        $response->assertRedirect(route('login'));
    }

    // ------------------------------------------------------------------
    // create
    // ------------------------------------------------------------------

    public function test_create_view_loads(): void
    {
        $response = $this->actingAs($this->user)->get(route('booking-enquiries.create'));
        $response->assertStatus(200);
    }

    // ------------------------------------------------------------------
    // store  (invoice booking → redirect to booking-enquiry-confirm)
    // ------------------------------------------------------------------

    public function test_store_invoice_booking_redirects_to_confirm(): void
    {
        $data = [
            'booking_type_admin' => 'invoice',
            'property_id'       => $this->property->id,
            'checkin'            => '2025-09-01',
            'checkout'           => '2025-09-05',
            'adults'             => 2,
            'name'               => 'John Smith',
            'email'              => 'john@example.com',
        ];

        $response = $this->actingAs($this->user)->post(route('booking-enquiries.store'), $data);

        $booking = BookingRequest::first();
        $this->assertNotNull($booking);

        $response->assertRedirect(route('booking-enquiry-confirm', $booking->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('booking_requests', [
            'property_id'       => $this->property->id,
            'booking_type_admin' => 'invoice',
            'booking_status'    => 'booked', // default for invoice
            'name'              => 'John Smith',
        ]);
    }

    // ------------------------------------------------------------------
    // store  (manual booking → redirect to index, status = booking-confirmed)
    // ------------------------------------------------------------------

    public function test_store_manual_booking_redirects_to_index(): void
    {
        $data = [
            'booking_type_admin' => 'manual',
            'property_id'       => $this->property->id,
            'checkin'            => '2025-09-10',
            'checkout'           => '2025-09-15',
            'adults'             => 3,
            'name'               => 'Alice Wonderland',
            'email'              => 'alice@example.com',
        ];

        $response = $this->actingAs($this->user)->post(route('booking-enquiries.store'), $data);

        $response->assertRedirect(route('booking-enquiries.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('booking_requests', [
            'property_id'    => $this->property->id,
            'booking_status' => 'booking-confirmed', // auto-confirmed for manual
            'name'           => 'Alice Wonderland',
        ]);
    }

    // ------------------------------------------------------------------
    // store  validation
    // ------------------------------------------------------------------

    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('booking-enquiries.store'), []);
        $response->assertSessionHasErrors(['booking_type_admin', 'property_id', 'checkin', 'checkout', 'name', 'email']);
    }

    public function test_store_validates_checkout_after_checkin(): void
    {
        $data = [
            'booking_type_admin' => 'invoice',
            'property_id'       => $this->property->id,
            'checkin'            => '2025-09-10',
            'checkout'           => '2025-09-05', // before checkin
            'name'               => 'Test',
            'email'              => 'test@example.com',
        ];

        $response = $this->actingAs($this->user)->post(route('booking-enquiries.store'), $data);
        $response->assertSessionHasErrors(['checkout']);
    }

    // ------------------------------------------------------------------
    // show  (redirects to index per legacy)
    // ------------------------------------------------------------------

    public function test_show_redirects_to_index(): void
    {
        $booking = BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin'      => '2025-08-01',
            'checkout'     => '2025-08-05',
            'name'         => 'Show Test',
            'email'        => 'show@example.com',
        ]);

        $response = $this->actingAs($this->user)->get(route('booking-enquiries.show', $booking));
        $response->assertRedirect(route('booking-enquiries.index'));
    }

    // ------------------------------------------------------------------
    // edit
    // ------------------------------------------------------------------

    public function test_edit_view_loads(): void
    {
        $booking = BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin'      => '2025-08-01',
            'checkout'     => '2025-08-05',
            'name'         => 'Edit Test',
            'email'        => 'edit@example.com',
        ]);

        $response = $this->actingAs($this->user)->get(route('booking-enquiries.edit', $booking));
        $response->assertStatus(200);
        $response->assertViewHas('data');
    }

    public function test_edit_invalid_id_redirects_with_danger(): void
    {
        $response = $this->actingAs($this->user)->get(route('booking-enquiries.edit', 99999));
        $response->assertRedirect(route('booking-enquiries.index'));
        $response->assertSessionHas('danger');
    }

    // ------------------------------------------------------------------
    // update
    // ------------------------------------------------------------------

    public function test_update_modifies_booking(): void
    {
        $booking = BookingRequest::create([
            'property_id'       => $this->property->id,
            'booking_type_admin' => 'invoice',
            'checkin'            => '2025-08-01',
            'checkout'           => '2025-08-05',
            'name'               => 'Old Name',
            'email'              => 'old@example.com',
        ]);

        $data = [
            'booking_type_admin' => 'invoice',
            'property_id'       => $this->property->id,
            'checkin'            => '2025-08-02',
            'checkout'           => '2025-08-06',
            'name'               => 'New Name',
            'email'              => 'new@example.com',
        ];

        $response = $this->actingAs($this->user)->put(route('booking-enquiries.update', $booking), $data);
        $response->assertRedirect(route('singlePropertyBookoing', $this->property->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('booking_requests', [
            'id'   => $booking->id,
            'name' => 'New Name',
        ]);
    }

    public function test_update_invalid_id_redirects_with_danger(): void
    {
        $data = [
            'booking_type_admin' => 'invoice',
            'property_id'       => $this->property->id,
            'checkin'            => '2025-08-02',
            'checkout'           => '2025-08-06',
            'name'               => 'Ghost',
            'email'              => 'ghost@example.com',
        ];

        $response = $this->actingAs($this->user)->put(route('booking-enquiries.update', 99999), $data);
        $response->assertRedirect(route('booking-enquiries.index'));
        $response->assertSessionHas('danger');
    }

    // ------------------------------------------------------------------
    // destroy  (cancel)
    // ------------------------------------------------------------------

    public function test_destroy_cancels_booking(): void
    {
        $booking = BookingRequest::create([
            'property_id'       => $this->property->id,
            'booking_type_admin' => 'invoice',
            'booking_status'    => 'booked',
            'checkin'            => '2025-08-01',
            'checkout'           => '2025-08-05',
            'name'               => 'Cancel Me',
            'email'              => 'cancel@example.com',
        ]);

        $response = $this->actingAs($this->user)->delete(route('booking-enquiries.destroy', $booking));
        $response->assertRedirect(route('singlePropertyBookoing', $this->property->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('booking_requests', [
            'id'             => $booking->id,
            'booking_status' => 'booking-cancel',
        ]);
    }

    public function test_destroy_invalid_id_redirects_with_danger(): void
    {
        $response = $this->actingAs($this->user)->delete(route('booking-enquiries.destroy', 99999));
        $response->assertRedirect(route('booking-enquiries.index'));
        $response->assertSessionHas('danger');
    }

    // ------------------------------------------------------------------
    // confirmed  (confirm / rental-aggrement)
    // ------------------------------------------------------------------

    public function test_confirmed_sets_rental_aggrement_status(): void
    {
        $booking = BookingRequest::create([
            'property_id'    => $this->property->id,
            'booking_status' => 'booked',
            'checkin'         => '2025-08-01',
            'checkout'        => '2025-08-05',
            'name'            => 'Confirm Me',
            'email'           => 'confirm@example.com',
        ]);

        $response = $this->actingAs($this->user)->get(route('booking-enquiry-confirm', $booking->id));
        $response->assertRedirect(route('singlePropertyBookoing', $this->property->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('booking_requests', [
            'id'             => $booking->id,
            'booking_status' => 'rental-aggrement',
        ]);
    }

    public function test_confirmed_invalid_id_redirects_with_danger(): void
    {
        $response = $this->actingAs($this->user)->get(route('booking-enquiry-confirm', 99999));
        $response->assertRedirect(route('booking-enquiries.index'));
        $response->assertSessionHas('danger');
    }

    // ------------------------------------------------------------------
    // singlePropertyBookoing  (per-property listing)
    // ------------------------------------------------------------------

    public function test_single_property_bookoing_shows_property_bookings(): void
    {
        BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin'      => '2025-08-01',
            'checkout'     => '2025-08-05',
            'name'         => 'Prop Booking',
            'email'        => 'prop@example.com',
        ]);

        $response = $this->actingAs($this->user)->get(route('singlePropertyBookoing', $this->property->id));
        $response->assertStatus(200);
        $response->assertViewHas('data');
    }

    public function test_single_property_bookoing_404_for_invalid_property(): void
    {
        $response = $this->actingAs($this->user)->get(route('singlePropertyBookoing', 99999));
        $response->assertStatus(404);
    }

    // ------------------------------------------------------------------
    // getCheckinCheckoutDataGaurav  (AJAX)
    // ------------------------------------------------------------------

    public function test_get_checkin_checkout_data_returns_json(): void
    {
        $response = $this->actingAs($this->user)->postJson(
            route('get-checkin-checkout-data-gaurav'),
            ['id' => $this->property->id]
        );

        $response->assertStatus(200);
        $response->assertJsonStructure(['checkin', 'checkout']);
    }

    // ------------------------------------------------------------------
    // Payments relationship smoke test via booking
    // ------------------------------------------------------------------

    public function test_booking_has_payments(): void
    {
        $booking = BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin'      => '2025-08-01',
            'checkout'     => '2025-08-05',
            'name'         => 'Payment Test',
            'email'        => 'pay@example.com',
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'tran_id'    => 'txn_abc123',
            'amount'     => '150.00',
            'status'     => 'completed',
            'type'       => 'stripe',
        ]);

        $this->assertCount(1, $booking->payments);
        $this->assertEquals('txn_abc123', $booking->payments->first()->tran_id);
    }
}
