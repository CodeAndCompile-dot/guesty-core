<?php

namespace App\Services;

use App\Models\BookingRequest;
use App\Models\IcalImportList;
use App\Models\Property;
use App\Services\Calendar\ICalService;
use App\Services\Communication\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * BookingService – business logic for creating, updating, confirming and
 * cancelling booking requests.
 *
 * Extracted from the legacy BookingRequestController to keep controllers thin.
 * Every public method returns a consistent result that the controller can act on.
 */
class BookingService
{
    public function __construct(
        protected ICalService $icalService,
        protected EmailService $emailService,
    ) {}

    /* ------------------------------------------------------------------ */
    /*  Listing                                                            */
    /* ------------------------------------------------------------------ */

    /**
     * All bookings, newest first.
     */
    public function listAll()
    {
        return BookingRequest::orderBy('id', 'desc')->get();
    }

    /**
     * Bookings for a single property, newest first.
     * Returns null when the property doesn't exist.
     */
    public function listForProperty(int $propertyId)
    {
        $property = Property::find($propertyId);

        if (! $property) {
            return null;
        }

        return BookingRequest::where('property_id', $propertyId)
            ->orderBy('id', 'desc')
            ->get();
    }

    /* ------------------------------------------------------------------ */
    /*  Create                                                             */
    /* ------------------------------------------------------------------ */

    /**
     * Create a new booking request.
     *
     * @return BookingRequest
     */
    public function store(array $data): BookingRequest
    {
        // If booking type is not "invoice" → auto-confirm as manual booking
        if (isset($data['booking_type_admin']) && $data['booking_type_admin'] !== 'invoice') {
            $data['booking_status'] = 'booking-confirmed';
        }

        return BookingRequest::create($data);
    }

    /* ------------------------------------------------------------------ */
    /*  Update                                                             */
    /* ------------------------------------------------------------------ */

    /**
     * Update an existing booking request.
     *
     * @return BookingRequest|null  null when booking not found
     */
    public function update(int $id, array $data): ?BookingRequest
    {
        $booking = BookingRequest::find($id);

        if (! $booking) {
            return null;
        }

        // If booking type is not "invoice" → auto-confirm
        if (isset($data['booking_type_admin']) && $data['booking_type_admin'] !== 'invoice') {
            $data['booking_status'] = 'booking-confirmed';
        }

        $booking->update($data);

        return $booking->fresh();
    }

    /* ------------------------------------------------------------------ */
    /*  Cancel (soft delete)                                               */
    /* ------------------------------------------------------------------ */

    /**
     * Cancel a booking: set status to 'booking-cancel', refresh iCal, send emails.
     *
     * @return array{success: bool, property_id: int|null, message: string}
     */
    public function cancel(int $id): array
    {
        $booking = BookingRequest::find($id);

        if (! $booking) {
            return ['success' => false, 'property_id' => null, 'message' => 'Booking not found'];
        }

        $propertyId = $booking->property_id;
        $booking->booking_status = 'booking-cancel';
        $booking->save();

        // Refresh iCal for the property
        $property = Property::find($propertyId);

        if ($property) {
            // Refresh all iCal imports for this property
            $imports = IcalImportList::where('property_id', $propertyId)->get();
            foreach ($imports as $import) {
                $this->icalService->refreshImport($propertyId, $import->ical_link, $import->id);
            }

            // Send cancellation emails
            if ($booking->booking_type_admin === 'invoice') {
                $this->sendCancelEmails($booking, $property);
            }
        }

        return ['success' => true, 'property_id' => $propertyId, 'message' => 'Booking cancelled'];
    }

    /* ------------------------------------------------------------------ */
    /*  Confirm (send payment request)                                     */
    /* ------------------------------------------------------------------ */

    /**
     * Confirm a booking: send confirmation/payment-request email, set status.
     *
     * @return array{success: bool, property_id: int|null, message: string}
     */
    public function confirm(int $id): array
    {
        $booking = BookingRequest::find($id);

        if (! $booking) {
            return ['success' => false, 'property_id' => null, 'message' => 'Booking not found'];
        }

        $property = Property::find($booking->property_id);

        if (! $property) {
            return ['success' => false, 'property_id' => null, 'message' => 'Property not found'];
        }

        // Send booking-confirmation email with Pay Now link
        $this->sendConfirmationEmail($booking, $property);

        $booking->booking_status = 'rental-aggrement';
        $booking->save();

        return [
            'success' => true,
            'property_id' => $booking->property_id,
            'message' => 'Confirmation sent',
        ];
    }

    /* ------------------------------------------------------------------ */
    /*  Find                                                               */
    /* ------------------------------------------------------------------ */

    public function find(int $id): ?BookingRequest
    {
        return BookingRequest::find($id);
    }

    /* ------------------------------------------------------------------ */
    /*  Email helpers                                                       */
    /* ------------------------------------------------------------------ */

    /**
     * Send booking-confirmation email (Pay Now link) to the customer.
     */
    protected function sendConfirmationEmail(BookingRequest $booking, Property $property): void
    {
        try {
            $data = $booking->toArray();
            $html = view('mail.booking-confirmation-user-email', compact('data', 'property'))->render();

            $this->emailService->sendRenderedHtml(
                $html,
                $booking->email,
                'Booking Confirmation — ' . ($property->name ?? 'Property'),
            );
        } catch (\Throwable $e) {
            Log::error('Booking confirmation email failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send cancellation emails to admin and customer.
     */
    protected function sendCancelEmails(BookingRequest $booking, Property $property): void
    {
        try {
            $data     = $booking->toArray();
            $adminTo  = \ModelHelper::getDataFromSetting('cancel_receiving_mail') ?? '';
            $subject  = 'Booking Cancelled — ' . ($property->name ?? 'Property');

            // Admin notification
            if ($adminTo) {
                $adminHtml = view('mail.booking-cancel-admin-email', compact('data', 'property'))->render();
                $this->emailService->sendRenderedHtml($adminHtml, $adminTo, $subject);
            }

            // Customer notification
            if (! empty($booking->email)) {
                $userHtml = view('mail.booking-cancel-user-email', compact('data', 'property'))->render();
                $this->emailService->sendRenderedHtml($userHtml, $booking->email, $subject);
            }
        } catch (\Throwable $e) {
            Log::error('Booking cancel email failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
