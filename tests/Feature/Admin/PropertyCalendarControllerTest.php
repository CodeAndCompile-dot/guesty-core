<?php

namespace Tests\Feature\Admin;

use App\Models\IcalEvent;
use App\Models\IcalImportList;
use App\Models\Property;
use App\Models\Location;
use App\Models\User;
use App\Services\Calendar\ICalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyCalendarControllerTest extends TestCase
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

    public function test_index_shows_ical_events(): void
    {
        IcalEvent::create([
            'ppp_id'         => $this->property->id,
            'ical_link'      => 'https://example.com/cal.ics',
            'start_date'     => '2025-07-01',
            'end_date'       => '2025-07-05',
            'text'           => 'Test Event',
            'event_pid'      => $this->property->id,
            'cat_id'         => 1,
            'uid'            => 'test-uid',
            'event_type'     => 'ical',
            'booking_status' => 1,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('properties-calendar.index', $this->property->id));

        $response->assertStatus(200);
        $response->assertViewHas('data');
        $response->assertViewHas('property');
    }

    public function test_index_redirects_for_invalid_property(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('properties-calendar.index', 99999));

        $response->assertRedirect(route('properties.index'));
    }

    public function test_create_shows_form(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('properties-calendar.create', $this->property->id));

        $response->assertStatus(200);
        $response->assertViewHas('property_id');
    }

    public function test_store_creates_import_and_refreshes(): void
    {
        // Mock ICalService to avoid actual HTTP calls
        $this->mock(ICalService::class, function ($mock) {
            $mock->shouldReceive('refreshImport')->once()->andReturn(0);
        });

        $response = $this->actingAs($this->user)
            ->post(route('properties-calendar.store', $this->property->id), [
                'ical_link' => 'https://example.com/unique-feed.ics',
            ]);

        $response->assertRedirect(route('properties-calendar.index', $this->property->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('ical_import_list', [
            'ical_link'   => 'https://example.com/unique-feed.ics',
            'property_id' => $this->property->id,
        ]);
    }

    public function test_store_validates_unique_ical_link(): void
    {
        IcalImportList::create([
            'ical_link'   => 'https://example.com/existing.ics',
            'property_id' => $this->property->id,
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('properties-calendar.store', $this->property->id), [
                'ical_link' => 'https://example.com/existing.ics',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('danger');
    }

    public function test_importlist_shows_feeds(): void
    {
        IcalImportList::create([
            'ical_link'   => 'https://example.com/feed.ics',
            'property_id' => $this->property->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('properties-calendar.import-list', $this->property->id));

        $response->assertStatus(200);
        $response->assertViewHas('data');
    }

    public function test_importlist_refresh_refreshes_feed(): void
    {
        $this->mock(ICalService::class, function ($mock) {
            $mock->shouldReceive('refreshImport')->once()->andReturn(5);
        });

        $importList = IcalImportList::create([
            'ical_link'   => 'https://example.com/refresh.ics',
            'property_id' => $this->property->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('properties-calendar.importlistRefresh', [
                $this->property->id,
                $importList->id,
            ]));

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_importlist_refresh_fails_for_invalid_id(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('properties-calendar.importlistRefresh', [
                $this->property->id,
                99999,
            ]));

        $response->assertRedirect();
        $response->assertSessionHas('danger');
    }

    public function test_self_ical_refresh(): void
    {
        $this->mock(ICalService::class, function ($mock) {
            $mock->shouldReceive('exportPropertyIcs')->once();
        });

        $response = $this->actingAs($this->user)
            ->get(route('properties-calendar.selfIcalRefresh', $this->property->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_destroy_deletes_import_and_events(): void
    {
        $importList = IcalImportList::create([
            'ical_link'   => 'https://example.com/delete-me.ics',
            'property_id' => $this->property->id,
        ]);

        IcalEvent::create([
            'ppp_id'         => $this->property->id,
            'ical_link'      => 'https://example.com/delete-me.ics',
            'start_date'     => '2025-07-01',
            'end_date'       => '2025-07-05',
            'text'           => 'Delete Test',
            'event_pid'      => $this->property->id,
            'cat_id'         => $importList->id,
            'uid'            => 'del-uid',
            'event_type'     => 'ical',
            'booking_status' => 1,
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('properties-calendar.destroy', [
                $this->property->id,
                $importList->id,
            ]));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('ical_import_list', ['id' => $importList->id]);
        $this->assertDatabaseMissing('ical_events', ['ical_link' => 'https://example.com/delete-me.ics']);
    }

    public function test_destroy_fails_for_invalid_id(): void
    {
        $response = $this->actingAs($this->user)
            ->delete(route('properties-calendar.destroy', [
                $this->property->id,
                99999,
            ]));

        $response->assertRedirect();
        $response->assertSessionHas('danger');
    }
}
