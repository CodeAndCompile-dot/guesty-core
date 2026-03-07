<?php

namespace App\Services\Calendar;

use App\Integrations\ICal\ICalExporter;
use App\Integrations\ICal\ICalParser;
use App\Models\IcalEvent;
use App\Models\IcalImportList;
use App\Models\Property;

/**
 * Orchestrates iCal import/export operations.
 * Replaces LiveCart::refreshIcalData(), allIcalImportListRefresh(), getFileIcalFileData().
 */
class ICalService
{
    public function __construct(
        protected ICalParser $parser,
        protected ICalExporter $exporter,
    ) {}

    /**
     * Refresh a single iCal import feed: delete old events, fetch + parse, save new events.
     */
    public function refreshImport(int $propertyId, string $icalLink, int $importListId): int
    {
        // Delete existing events for this property + link
        IcalEvent::where([
            'ppp_id'    => $propertyId,
            'event_pid' => $propertyId,
            'ical_link' => $icalLink,
        ])->delete();

        $events = $this->parser->parseUrl($icalLink);
        $count = 0;

        foreach ($events as $event) {
            if (! isset($event['start_date'], $event['end_date'])) {
                continue;
            }

            $startDate = date('Y-m-d', strtotime($event['start_date']));
            $endDate = date('Y-m-d', strtotime($event['end_date']));

            // Delete any duplicate based on same dates + property + link
            IcalEvent::where([
                'ppp_id'     => $propertyId,
                'ical_link'  => $icalLink,
                'start_date' => $startDate,
                'end_date'   => $endDate,
                'event_pid'  => $propertyId,
                'cat_id'     => $importListId,
            ])->delete();

            IcalEvent::create([
                'ppp_id'         => $propertyId,
                'ical_link'      => $icalLink,
                'start_date'     => $startDate,
                'end_date'       => $endDate,
                'text'           => $event['text'] ?? '',
                'event_pid'      => $propertyId,
                'cat_id'         => $importListId,
                'uid'            => $event['event_id'] ?? '',
                'event_type'     => 'ical',
                'booking_status' => 1,
            ]);

            $count++;
        }

        return $count;
    }

    /**
     * Refresh ALL iCal import feeds for ALL properties
     * and re-export .ics files for each property.
     */
    public function refreshAllImports(): void
    {
        $imports = IcalImportList::all();

        foreach ($imports as $import) {
            $this->refreshImport(
                (int) $import->property_id,
                $import->ical_link,
                $import->id
            );
        }

        // Re-export .ics files for each property
        foreach (Property::all() as $property) {
            $this->exportPropertyIcs($property->id);
        }
    }

    /**
     * Export confirmed website events for a property to an .ics file.
     * Legacy: getEventsICalObject (exports from ical_events where event_type='website')
     */
    public function exportWebsiteEvents(int $propertyId): void
    {
        $events = IcalEvent::where([
            'event_type' => 'website',
            'event_pid'  => $propertyId,
        ])->get()->toArray();

        $icsContent = $this->exporter->buildCalendar($events);
        $filePath = $this->exporter->getPropertyIcsPath($propertyId);

        $this->exporter->writeToFile($icsContent, $filePath);
    }

    /**
     * Export confirmed bookings for a property to an .ics file.
     * Legacy: getFileIcalFileData() — uses BookingRequest model.
     * Note: BookingRequest model will be created in Phase 7.
     * For now this exports from IcalEvent data.
     */
    public function exportPropertyIcs(int $propertyId): void
    {
        // Phase 7 will add BookingRequest-based export.
        // For now, export website events.
        $this->exportWebsiteEvents($propertyId);
    }
}
