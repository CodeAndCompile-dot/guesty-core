<?php

namespace App\Services\Communication;

use App\Models\BookingRequest;
use App\Models\Property;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * WelcomePackageService — sends welcome-package emails X days before check-in.
 *
 * Legacy: ICalController::sendWelcomePackage1()
 */
class WelcomePackageService
{
    public function __construct(
        protected EmailService $emailService,
    ) {}

    /**
     * Process all qualifying bookings and send welcome-package emails.
     *
     * @return int  Number of emails sent
     */
    public function process(): int
    {
        $daysOut = (int) (\ModelHelper::getDataFromSetting('welcome_package_send_day') ?? 3);
        $date    = Carbon::today()->addDays($daysOut)->toDateString();
        $sent    = 0;

        $bookings = BookingRequest::where('booking_status', 'booking-confirmed')
            ->where('welcome_email', 'false')
            ->where('booking_type_admin', 'invoice')
            ->where('checkin', $date)
            ->get();

        foreach ($bookings as $booking) {
            try {
                $property = Property::find($booking->property_id);
                if (! $property) {
                    continue;
                }

                $data          = $booking->toArray();
                $adminTo       = \ModelHelper::getDataFromSetting('welcome_package_receiving_mail') ?? '';
                $adminSubject  = 'Welcome Package — ' . ($property->name ?? 'Property');
                $customerTo    = $booking->email;
                $customerSub   = 'Welcome Package — ' . ($property->name ?? 'Property');

                $attachments = [];
                if ($property->welcome_package_attachment) {
                    $path = public_path('uploads/properties/' . $property->welcome_package_attachment);
                    if (file_exists($path)) {
                        $attachments[] = $path;
                    }
                }

                // Admin email
                $adminHtml = view('mail.welcome-package-admin', compact('property', 'data'))->render();
                $this->emailService->sendRenderedHtml($adminHtml, $adminTo, $adminSubject, $attachments);

                // Customer email
                $customerHtml = view('mail.welcome-package-customer', compact('property', 'data'))->render();
                $this->emailService->sendRenderedHtml($customerHtml, $customerTo, $customerSub, $attachments);

                BookingRequest::where('id', $booking->id)->update(['welcome_email' => 'true']);
                $sent++;
            } catch (\Throwable $e) {
                Log::error('Welcome package email failed', [
                    'booking_id' => $booking->id,
                    'error'      => $e->getMessage(),
                ]);
            }
        }

        return $sent;
    }
}
