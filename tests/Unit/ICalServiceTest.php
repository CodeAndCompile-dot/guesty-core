<?php

namespace Tests\Unit;

use App\Integrations\ICal\ICalParser;
use App\Models\IcalEvent;
use App\Models\IcalImportList;
use App\Models\Location;
use App\Models\Property;
use App\Services\Calendar\ICalService;
use App\Integrations\ICal\ICalExporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ICalServiceTest extends TestCase
{
    use RefreshDatabase;

    private Property $property;

    protected function setUp(): void
    {
        parent::setUp();

        $location = Location::create([
            'name' => 'SvcLoc', 'seo_url' => 'svcloc', 'parent_id' => null, 'status' => 1,
        ]);

        $this->property = Property::create([
            'name'        => 'SvcProp',
            'seo_url'     => 'svcprop',
            'location_id' => $location->id,
            'status'      => 1,
        ]);
    }

    public function test_refresh_import_saves_events(): void
    {
        $mockParser = $this->mock(ICalParser::class);
        $mockParser->shouldReceive('parseUrl')
            ->with('https://example.com/test.ics')
            ->once()
            ->andReturn([
                [
                    'start_date' => '2025-07-01',
                    'end_date'   => '2025-07-05',
                    'text'       => 'Mock Event',
                    'event_id'   => 'mock-uid-1',
                ],
                [
                    'start_date' => '2025-07-10',
                    'end_date'   => '2025-07-12',
                    'text'       => 'Mock Event 2',
                    'event_id'   => 'mock-uid-2',
                ],
            ]);

        $service = new ICalService($mockParser, app(ICalExporter::class));

        $importList = IcalImportList::create([
            'ical_link'   => 'https://example.com/test.ics',
            'property_id' => $this->property->id,
        ]);

        $count = $service->refreshImport(
            $this->property->id,
            'https://example.com/test.ics',
            $importList->id
        );

        $this->assertEquals(2, $count);
        $this->assertDatabaseHas('ical_events', [
            'event_pid' => $this->property->id,
            'uid'       => 'mock-uid-1',
        ]);
    }

    public function test_refresh_import_deletes_old_events_first(): void
    {
        // Pre-existing event
        IcalEvent::create([
            'ppp_id'         => $this->property->id,
            'ical_link'      => 'https://example.com/old.ics',
            'start_date'     => '2025-06-01',
            'end_date'       => '2025-06-05',
            'text'           => 'Old Event',
            'event_pid'      => $this->property->id,
            'cat_id'         => 1,
            'uid'            => 'old-uid',
            'event_type'     => 'ical',
            'booking_status' => 1,
        ]);

        $mockParser = $this->mock(ICalParser::class);
        $mockParser->shouldReceive('parseUrl')->andReturn([]);

        $service = new ICalService($mockParser, app(ICalExporter::class));

        $service->refreshImport(
            $this->property->id,
            'https://example.com/old.ics',
            1
        );

        $this->assertDatabaseMissing('ical_events', ['uid' => 'old-uid']);
    }

    public function test_refresh_import_skips_events_without_dates(): void
    {
        $mockParser = $this->mock(ICalParser::class);
        $mockParser->shouldReceive('parseUrl')
            ->andReturn([
                ['text' => 'No dates event'],
                ['start_date' => '2025-07-01', 'end_date' => '2025-07-02', 'text' => 'Valid'],
            ]);

        $service = new ICalService($mockParser, app(ICalExporter::class));

        $count = $service->refreshImport($this->property->id, 'https://example.com/x.ics', 1);

        $this->assertEquals(1, $count);
    }
}
