<?php

namespace Tests\Unit;

use App\Integrations\ICal\ICalParser;
use App\Integrations\ICal\ICalExporter;
use Tests\TestCase;

class ICalParserTest extends TestCase
{
    private ICalParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new ICalParser();
    }

    public function test_parse_string_extracts_events(): void
    {
        $ics = <<<ICS
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//Test//Test//EN
BEGIN:VEVENT
DTSTART:20250701T120000Z
DTEND:20250705T120000Z
SUMMARY:Test Booking
UID:test-uid-123
END:VEVENT
BEGIN:VEVENT
DTSTART:20250710
DTEND:20250712
SUMMARY:Second Booking
UID:test-uid-456
END:VEVENT
END:VCALENDAR
ICS;

        $events = $this->parser->parseString($ics);

        // parseString uses 1-based keys from explode
        $values = array_values($events);
        $this->assertCount(2, $values);
        $this->assertEquals('Test Booking', $values[0]['text']);
        $this->assertEquals('test-uid-123', $values[0]['event_id']);
        $this->assertNotEmpty($values[0]['start_date']);
        $this->assertNotEmpty($values[0]['end_date']);
    }

    public function test_parse_string_handles_empty_calendar(): void
    {
        $ics = <<<ICS
BEGIN:VCALENDAR
VERSION:2.0
END:VCALENDAR
ICS;

        $events = $this->parser->parseString($ics);

        $this->assertIsArray($events);
        $this->assertCount(0, $events);
    }

    public function test_get_mysql_date_converts_ical_format(): void
    {
        // iCal compact date format: 20250701T120000Z
        $result = $this->parser->getMySQLDate('20250701T120000Z');

        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}/', $result);
    }

    public function test_get_mysql_date_handles_date_only(): void
    {
        $result = $this->parser->getMySQLDate('20250701');

        $this->assertEquals('2025-07-01 00:00:00', $result);
    }

    public function test_get_convert_day_maps_ical_day(): void
    {
        // getConvertDay(index) returns 2-letter code, getConvertDay(code, true) returns numeric index
        $this->assertEquals('MO', $this->parser->getConvertDay(1));
        $this->assertEquals('SU', $this->parser->getConvertDay(0));
        $this->assertEquals('FR', $this->parser->getConvertDay(5));
        // Reverse: 2-letter code => index
        $this->assertEquals(1, $this->parser->getConvertDay('MO', true));
        $this->assertEquals(0, $this->parser->getConvertDay('SU', true));
    }

    public function test_get_convert_type_maps_frequency(): void
    {
        // getConvertType(key) returns iCal string, getConvertType(icalString, true) returns key
        $this->assertEquals('DAILY', $this->parser->getConvertType('day'));
        $this->assertEquals('WEEKLY', $this->parser->getConvertType('week'));
        // Reverse: iCal string => key
        $this->assertEquals('day', $this->parser->getConvertType('DAILY', true));
        $this->assertEquals('week', $this->parser->getConvertType('WEEKLY', true));
        $this->assertEquals('month', $this->parser->getConvertType('MONTHLY', true));
        $this->assertEquals('year', $this->parser->getConvertType('YEARLY', true));
    }

    public function test_exporter_builds_calendar_string(): void
    {
        $exporter = new ICalExporter();

        $events = [
            [
                'start_date'  => '2025-07-01',
                'end_date'    => '2025-07-05',
                'text'        => 'Test Booking',
                'id'          => 1,
                'created_at'  => '2025-06-01 00:00:00',
                'updated_at'  => '2025-06-01 00:00:00',
            ],
        ];

        $output = $exporter->buildCalendar($events);

        $this->assertStringContainsString('BEGIN:VCALENDAR', $output);
        $this->assertStringContainsString('BEGIN:VEVENT', $output);
        $this->assertStringContainsString('Test Booking', $output);
        $this->assertStringContainsString('END:VCALENDAR', $output);
    }

    public function test_exporter_get_property_ics_path(): void
    {
        $exporter = new ICalExporter();

        $path = $exporter->getPropertyIcsPath(42);

        $this->assertStringEndsWith('000042.ics', $path);
        $this->assertStringContainsString('uploads/ical', $path);
    }
}
