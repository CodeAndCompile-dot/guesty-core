<?php

namespace Tests\Unit;

use App\Models\GuestyAvailabilityPrice;
use App\Models\GuestyProperty;
use App\Models\GuestyPropertyBooking;
use App\Models\IcalEvent;
use App\Models\Location;
use App\Models\Property;
use App\Services\Calendar\AvailabilityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvailabilityServiceTest extends TestCase
{
    use RefreshDatabase;

    private AvailabilityService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AvailabilityService();
    }

    public function test_date_range_generates_inclusive_dates(): void
    {
        $dates = $this->service->dateRange('2025-07-01', '2025-07-05');

        $this->assertCount(5, $dates);
        $this->assertEquals('2025-07-01', $dates[0]);
        $this->assertEquals('2025-07-05', $dates[4]);
    }

    public function test_date_range_single_day(): void
    {
        $dates = $this->service->dateRange('2025-07-01', '2025-07-01');

        $this->assertCount(1, $dates);
        $this->assertEquals('2025-07-01', $dates[0]);
    }

    public function test_checkin_checkout_from_ical_events(): void
    {
        $location = Location::create([
            'name' => 'Test', 'seo_url' => 'test', 'parent_id' => null, 'status' => 1,
        ]);
        $property = Property::create([
            'name' => 'Prop', 'seo_url' => 'prop', 'location_id' => $location->id, 'status' => 1,
        ]);

        IcalEvent::create([
            'ppp_id'         => $property->id,
            'ical_link'      => 'https://example.com/cal.ics',
            'start_date'     => date('Y-m-d', strtotime('+1 day')),
            'end_date'       => date('Y-m-d', strtotime('+5 days')),
            'text'           => 'Test',
            'event_pid'      => $property->id,
            'cat_id'         => 1,
            'uid'            => 'uid-1',
            'event_type'     => 'ical',
            'booking_status' => 1,
        ]);

        $result = $this->service->getCheckInCheckOut($property->id);

        $this->assertArrayHasKey('checkin', $result);
        $this->assertArrayHasKey('checkout', $result);
        $this->assertNotEmpty($result['checkin']);
        $this->assertNotEmpty($result['checkout']);
    }

    public function test_checkin_checkout_guesty_overwrites_ical(): void
    {
        $location = Location::create([
            'name' => 'Loc', 'seo_url' => 'loc', 'parent_id' => null, 'status' => 1,
        ]);
        $property = Property::create([
            'name' => 'GProp', 'seo_url' => 'gprop', 'location_id' => $location->id, 'status' => 1,
        ]);

        // Create iCal event (will be overwritten by Guesty)
        IcalEvent::create([
            'ppp_id'         => $property->id,
            'ical_link'      => 'https://example.com/cal.ics',
            'start_date'     => date('Y-m-d', strtotime('+1 day')),
            'end_date'       => date('Y-m-d', strtotime('+3 days')),
            'text'           => 'iCal Event',
            'event_pid'      => $property->id,
            'cat_id'         => 1,
            'uid'            => 'uid-ical',
            'event_type'     => 'ical',
            'booking_status' => 1,
        ]);

        // Create GuestyProperty — this triggers the overwrite behaviour
        GuestyProperty::create([
            'id'      => $property->id,
            'title'   => 'Guesty Prop',
            'seo_url' => 'guesty-prop',
            '_id'     => 'guesty-listing-abc',
            'status'  => 'true',
        ]);

        GuestyPropertyBooking::create([
            'listingId'  => 'guesty-listing-abc',
            'start_date' => date('Y-m-d', strtotime('+10 days')),
            'end_date'   => date('Y-m-d', strtotime('+15 days')),
        ]);

        $result = $this->service->getCheckInCheckOut($property->id);

        // The Guesty booking dates should be present (iCal data was overwritten per legacy bug)
        $this->assertNotEmpty($result['checkin']);
        // iCal start_date (+1 day) should NOT be in checkin array (overwritten)
        $icalStartDate = date('Y-m-d', strtotime('+1 day'));
        $this->assertNotContains($icalStartDate, $result['checkin']);
    }

    public function test_checkin_checkout_blocked(): void
    {
        $location = Location::create([
            'name' => 'BLoc', 'seo_url' => 'bloc', 'parent_id' => null, 'status' => 1,
        ]);
        $property = Property::create([
            'name' => 'BProp', 'seo_url' => 'bprop', 'location_id' => $location->id, 'status' => 1,
        ]);

        IcalEvent::create([
            'ppp_id'         => $property->id,
            'ical_link'      => 'https://example.com/b.ics',
            'start_date'     => date('Y-m-d', strtotime('+1 day')),
            'end_date'       => date('Y-m-d', strtotime('+5 days')),
            'text'           => 'Blocked Test',
            'event_pid'      => $property->id,
            'cat_id'         => 1,
            'uid'            => 'uid-blocked',
            'event_type'     => 'ical',
            'booking_status' => 1,
        ]);

        $result = $this->service->getCheckInCheckOutBlocked($property->id);

        $this->assertArrayHasKey('checkin', $result);
        $this->assertArrayHasKey('checkout', $result);
        $this->assertArrayHasKey('blocked', $result);
        // Blocked should contain dates between start+1 and end-1
        $this->assertNotEmpty($result['blocked']);
    }

    public function test_unavailable_guesty_dates_added_to_blocked(): void
    {
        $location = Location::create([
            'name' => 'ULoc', 'seo_url' => 'uloc', 'parent_id' => null, 'status' => 1,
        ]);
        $property = Property::create([
            'name' => 'UProp', 'seo_url' => 'uprop', 'location_id' => $location->id, 'status' => 1,
        ]);

        GuestyProperty::create([
            'id'      => $property->id,
            'title'   => 'Guesty Unavail',
            'seo_url' => 'guesty-unavail',
            '_id'     => 'guesty-unavail-id',
            'status'  => 'true',
        ]);

        GuestyAvailabilityPrice::create([
            'listingId'  => 'guesty-unavail-id',
            'start_date' => date('Y-m-d', strtotime('+2 days')),
            'status'     => 'unavailable',
            'price'      => 0,
        ]);

        $result = $this->service->getCheckInCheckOutBlocked($property->id);

        $unavailDate = date('Y-m-d', strtotime('+2 days'));
        $this->assertContains($unavailDate, $result['blocked']);
    }
}
