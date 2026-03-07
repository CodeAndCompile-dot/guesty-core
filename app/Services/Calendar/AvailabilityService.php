<?php

namespace App\Services\Calendar;

use App\Models\BookingRequest;
use App\Models\GuestyAvailabilityPrice;
use App\Models\GuestyProperty;
use App\Models\GuestyPropertyBooking;
use App\Models\IcalEvent;

/**
 * Availability date-array calculation for datepickers.
 * Ports LiveCart::iCalDataCheckInCheckOut() and iCalDataCheckInCheckOutCheckinCheckout().
 *
 * IMPORTANT: The legacy behaviour where Guesty data OVERWRITES iCal data ($checkin=[];$checkout=[])
 * when a GuestyProperty exists is preserved intentionally for backward compatibility.
 */
class AvailabilityService
{
    /**
     * Ensure a date value is a Y-m-d string (handles Carbon, DateTime, or string).
     */
    protected function toDateString($date): string
    {
        if ($date instanceof \DateTimeInterface) {
            return $date->format('Y-m-d');
        }

        return (string) $date;
    }

    /**
     * Generate inclusive date range array.
     * Ports LiveCart::DifferentDates().
     *
     * @return string[]
     */
    public function dateRange(string $start, string $end, string $format = 'Y-m-d'): array
    {
        $array = [];
        $interval = new \DateInterval('P1D');
        $realEnd = new \DateTime($end);
        $realEnd->add($interval);
        $period = new \DatePeriod(new \DateTime($start), $interval, $realEnd);

        foreach ($period as $date) {
            $array[] = $date->format($format);
        }

        return $array;
    }

    /**
     * Get checkin/checkout date arrays for a property (for disabling dates in datepicker).
     * Ports LiveCart::iCalDataCheckInCheckOut() EXACTLY — including legacy Guesty overwrite bug.
     *
     * @return array{checkin: string[], checkout: string[]}
     */
    public function getCheckInCheckOut(int $propertyId): array
    {
        $today = date('Y-m-d');

        // Step 1: iCal events
        $data = IcalEvent::where(['event_pid' => $propertyId])
            ->where('end_date', '>', $today)
            ->get();

        $checkin = [];
        $checkout = [];

        foreach ($data as $row) {
            $todate = date('Y-m-d', strtotime('-1 days', strtotime($row->end_date)));
            $fdate = date('Y-m-d', strtotime('+1 days', strtotime($row->start_date)));
            $checkin = array_merge($checkin, $this->dateRange($row->start_date, $todate));
            $checkout = array_merge($checkout, $this->dateRange($fdate, $row->end_date));
        }

        // Step 2: Guesty bookings — LEGACY BUG: resets $checkin/$checkout when property exists
        $property = GuestyProperty::find($propertyId);

        if ($property) {
            $data = GuestyPropertyBooking::where(['listingId' => $property->_id])
                ->where('end_date', '>', $today)
                ->get();

            // Legacy overwrites iCal data here (bug #12 preserved for compatibility)
            $checkin = [];
            $checkout = [];

            foreach ($data as $row) {
                $todate = date('Y-m-d', strtotime('-1 days', strtotime($row->end_date)));
                $fdate = date('Y-m-d', strtotime('+1 days', strtotime($row->start_date)));
                $checkin = array_merge($checkin, $this->dateRange($row->start_date, $todate));
                $checkout = array_merge($checkout, $this->dateRange($fdate, $row->end_date));
            }
        }

        // Step 3: BookingRequest (booking-confirmed123) — dead code bug preserved
        // Legacy uses booking_status="booking-confirmed123" which never matches any real data.
        $data = BookingRequest::where(['booking_status' => 'booking-confirmed123', 'property_id' => $propertyId])
            ->where('checkout', '>', $today)
            ->get();

        foreach ($data as $row) {
            $todate = date('Y-m-d', strtotime('-1 days', strtotime($row->checkout)));
            $fdate = date('Y-m-d', strtotime('+1 days', strtotime($row->checkin)));
            $checkin = array_merge($checkin, $this->dateRange($row->checkin, $todate));
            $checkout = array_merge($checkout, $this->dateRange($fdate, $row->checkout));
        }

        sort($checkin);
        sort($checkout);

        // Step 4: Guesty availability prices
        if ($property) {
            // Unavailable dates
            $unavailable = GuestyAvailabilityPrice::where(['listingId' => $property->_id])
                ->whereIn('status', ['unavailable'])
                ->get();

            foreach ($unavailable as $u) {
                $checkin[] = $this->toDateString($u->start_date);
                $checkout[] = $this->toDateString($u->start_date);
            }

            // Booked dates (only if previous day is NOT available)
            $booked = GuestyAvailabilityPrice::where(['listingId' => $property->_id])
                ->whereIn('status', ['booked'])
                ->get();

            foreach ($booked as $u) {
                $prevDate = date('Y-m-d', strtotime('-1 day', strtotime($u->start_date)));
                $prevAvailable = GuestyAvailabilityPrice::where(['listingId' => $property->_id])
                    ->whereIn('status', ['available'])
                    ->where('start_date', $prevDate)
                    ->first();

                if (! $prevAvailable) {
                    $checkin[] = $this->toDateString($u->start_date);
                    $checkout[] = $this->toDateString($u->start_date);
                }
            }

            sort($checkin);
            sort($checkout);
        }

        return ['checkin' => $checkin, 'checkout' => $checkout];
    }

    /**
     * Get checkin/checkout/blocked date arrays (for front-end datepicker with turnover days).
     * Ports LiveCart::iCalDataCheckInCheckOutCheckinCheckout() EXACTLY.
     *
     * @return array{checkin: string[], checkout: string[], blocked: string[]}
     */
    public function getCheckInCheckOutBlocked(int $propertyId): array
    {
        $today = date('Y-m-d');

        // Step 1: iCal events
        $data = IcalEvent::where(['event_pid' => $propertyId])
            ->where('end_date', '>', $today)
            ->get();

        $checkin = [];
        $checkout = [];
        $blocked = [];

        foreach ($data as $row) {
            $checkin[] = $this->toDateString($row->start_date);
            $checkout[] = $this->toDateString($row->end_date);

            $now = strtotime($row->start_date);
            $yourDate = strtotime($row->end_date);
            $datediff = $yourDate - $now;
            $day = ceil($datediff / (60 * 60 * 24));

            for ($i = 1; $i < $day; $i++) {
                $date = strtotime($row->start_date);
                $date = strtotime("+{$i} day", $date);
                $blocked[] = date('Y-m-d', $date);
            }
        }

        // Step 2: Guesty bookings — LEGACY BUG: resets $checkin/$checkout (not $blocked)
        $property = GuestyProperty::find($propertyId);

        if ($property) {
            $data = GuestyPropertyBooking::where(['listingId' => $property->_id])
                ->where('end_date', '>', $today)
                ->get();

            // Legacy overwrites $checkin/$checkout here (bug preserved)
            $checkin = [];
            $checkout = [];

            foreach ($data as $row) {
                $checkin[] = $this->toDateString($row->start_date);
                $checkout[] = $this->toDateString($row->end_date);

                $now = strtotime($row->start_date);
                $yourDate = strtotime($row->end_date);
                $datediff = $yourDate - $now;
                $day = ceil($datediff / (60 * 60 * 24));

                for ($i = 1; $i < $day; $i++) {
                    $date = strtotime($row->start_date);
                    $date = strtotime("+{$i} day", $date);
                    $blocked[] = date('Y-m-d', $date);
                }
            }
        }

        // Step 3: Guesty availability prices
        if ($property) {
            // Booked (only if previous day not available)
            $booked = GuestyAvailabilityPrice::where(['listingId' => $property->_id])
                ->whereIn('status', ['booked'])
                ->get();

            foreach ($booked as $u) {
                $prevDate = date('Y-m-d', strtotime('-1 day', strtotime($u->start_date)));
                $prevAvailable = GuestyAvailabilityPrice::where(['listingId' => $property->_id])
                    ->whereIn('status', ['available'])
                    ->where('start_date', $prevDate)
                    ->first();

                if (! $prevAvailable) {
                    $blocked[] = $this->toDateString($u->start_date);
                }
            }

            sort($checkin);
            sort($checkout);

            // Unavailable
            $unavailable = GuestyAvailabilityPrice::where(['listingId' => $property->_id])
                ->whereIn('status', ['unavailable'])
                ->get();

            foreach ($unavailable as $u) {
                $blocked[] = $this->toDateString($u->start_date);
            }

            sort($checkin);
            sort($checkout);
        }

        // Step 4: BookingRequest (booking-confirmed) — adds checkin/checkout days + blocked (intermediate)
        $data = BookingRequest::where(['booking_status' => 'booking-confirmed', 'property_id' => $propertyId])
            ->where('checkout', '>', $today)
            ->get();

        foreach ($data as $row) {
            $checkin[] = $row->checkin;
            $checkout[] = $row->checkout;

            $now = strtotime($row->checkin);
            $yourDate = strtotime($row->checkout);
            $datediff = $yourDate - $now;
            $day = ceil($datediff / (60 * 60 * 24));

            for ($i = 1; $i < $day; $i++) {
                $date = strtotime($row->checkin);
                $date = strtotime("+{$i} day", $date);
                $blocked[] = date('Y-m-d', $date);
            }
        }

        // Step 5: Intersection logic — dates that are both checkin AND checkout become blocked
        $result = array_intersect($checkin, $checkout);
        $blocked = array_merge($blocked, $result);

        foreach ($checkin as $key => $c) {
            if (in_array($c, $result)) {
                unset($checkin[$key]);
            }
        }

        foreach ($checkout as $key => $c) {
            if (in_array($c, $result)) {
                unset($checkout[$key]);
            }
        }

        sort($checkin);
        sort($checkout);
        sort($blocked);

        return ['checkin' => $checkin, 'checkout' => $checkout, 'blocked' => $blocked];
    }
}
