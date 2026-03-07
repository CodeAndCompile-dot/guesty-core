<?php

namespace App\Integrations\ICal;

/**
 * Exports events to iCalendar (.ics) format.
 * Ported from legacy LiveCart::getFileIcalFileData(), getEventsICalObject().
 */
class ICalExporter
{
    protected const ICAL_FORMAT = 'Ymd\THis\Z';

    /**
     * Build a VCALENDAR string from an array of events.
     *
     * Each event should have: start_date, end_date, text, id, created_at, updated_at
     */
    public function buildCalendar(array $events, ?string $appHost = null): string
    {
        $host = $appHost ?? config('app.url', 'Laravel');

        $ical = "BEGIN:VCALENDAR\r\n";
        $ical .= "VERSION:2.0\r\n";
        $ical .= "METHOD:PUBLISH\r\n";
        $ical .= "PRODID:-//Laravel//Webdesignvr//EN\r\n";

        foreach ($events as $event) {
            $uid = time() . '#' . ($event['id'] ?? 0) . '#saro@' . $host;
            $startDate = $event['start_date'] ?? $event['checkin'] ?? '';
            $endDate = $event['end_date'] ?? $event['checkout'] ?? '';
            $summary = $event['text'] ?? $event['name'] ?? '';

            $ical .= "BEGIN:VEVENT\r\n";
            $ical .= 'DTSTART:' . date(self::ICAL_FORMAT, strtotime($startDate)) . "\r\n";
            $ical .= 'DTEND:' . date(self::ICAL_FORMAT, strtotime($endDate)) . "\r\n";
            $ical .= 'DTSTAMP:' . date(self::ICAL_FORMAT, strtotime($event['created_at'] ?? 'now')) . "\r\n";
            $ical .= "SUMMARY:{$summary}\r\n";
            $ical .= "DESCRIPTION:{$summary}\r\n";
            $ical .= "UID:{$uid}\r\n";
            $ical .= "STATUS:CONFIRMED\r\n";
            $ical .= 'LAST-MODIFIED:' . date(self::ICAL_FORMAT, strtotime($event['updated_at'] ?? 'now')) . "\r\n";
            $ical .= "END:VEVENT\r\n";
        }

        $ical .= 'END:VCALENDAR';

        return $ical;
    }

    /**
     * Write a VCALENDAR to a file at the given path.
     */
    public function writeToFile(string $icalContent, string $filePath): void
    {
        $dir = dirname($filePath);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents($filePath, $icalContent);
    }

    /**
     * Get the standard .ics file path for a property.
     * Legacy format: zero-padded 6-digit ID.
     */
    public function getPropertyIcsPath(int $propertyId): string
    {
        $file = sprintf('%06d', $propertyId);

        return public_path("uploads/ical/{$file}.ics");
    }
}
