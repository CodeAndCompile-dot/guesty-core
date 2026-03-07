<?php

namespace App\Helpers;

use App\Services\Calendar\AvailabilityService;
use App\Services\Calendar\ICalService;

/**
 * LiveCart helper — thin wrapper delegating to ICalService and AvailabilityService.
 * Preserves backward compatibility with the LiveCart facade used in legacy views/controllers.
 */
class LiveCart
{
    public function __construct(
        protected ICalService $icalService,
        protected AvailabilityService $availabilityService,
    ) {}

    /**
     * Generate inclusive date range.
     */
    public function DifferentDates(string $start, string $end, string $format = 'Y-m-d'): array
    {
        return $this->availabilityService->dateRange($start, $end, $format);
    }

    /**
     * Get checkin/checkout arrays for datepicker.
     *
     * @return array{checkin: string[], checkout: string[]}
     */
    public function iCalDataCheckInCheckOut(int $propertyId): array
    {
        return $this->availabilityService->getCheckInCheckOut($propertyId);
    }

    /**
     * Get checkin/checkout/blocked arrays for datepicker with turnover days.
     *
     * @return array{checkin: string[], checkout: string[], blocked: string[]}
     */
    public function iCalDataCheckInCheckOutCheckinCheckout(int $propertyId): array
    {
        return $this->availabilityService->getCheckInCheckOutBlocked($propertyId);
    }

    /**
     * Refresh a single iCal import feed.
     */
    public function refreshIcalData(int $propertyId, string $icalLink, int $importListId): int
    {
        return $this->icalService->refreshImport($propertyId, $icalLink, $importListId);
    }

    /**
     * Refresh ALL iCal import feeds and re-export .ics files.
     */
    public function allIcalImportListRefresh(): void
    {
        $this->icalService->refreshAllImports();
    }

    /**
     * Export confirmed bookings for a property to an .ics file.
     */
    public function getFileIcalFileData(int $propertyId): void
    {
        $this->icalService->exportPropertyIcs($propertyId);
    }

    /**
     * Export website events for a property to an .ics file.
     */
    public function getEventsICalObject(int $propertyId): void
    {
        $this->icalService->exportWebsiteEvents($propertyId);
    }
}
