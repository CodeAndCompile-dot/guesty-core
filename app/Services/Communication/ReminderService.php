<?php

namespace App\Services\Communication;

use App\Models\BookingRequest;
use App\Models\Property;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * ReminderService — sends payment-reminder emails for instalment bookings.
 *
 * Legacy: ICalController::sendReminderPackage1()
 *
 * Three scenarios:
 *  1) 2-payment booking, 1 done → 2nd payment reminder
 *  2) 3-payment booking, 1 done → 2nd payment reminder
 *  3) 3-payment booking, 2 done → 3rd payment reminder
 */
class ReminderService
{
    public function __construct(
        protected EmailService $emailService,
    ) {}

    /**
     * @return int  Number of bookings reminded
     */
    public function process(): int
    {
        $sent = 0;

        // --- Scenario 1: 2-payment bookings, 1st done ---
        $days2 = (int) (\ModelHelper::getDataFromSetting('second_how_many_days') ?? 30);
        $date2 = Carbon::today()->addDays($days2)->toDateString();

        $bookings2 = BookingRequest::where('booking_status', 'booking-confirmed')
            ->where('booking_type_admin', 'invoice')
            ->where('total_payment', 2)
            ->where('how_many_payment_done', 1)
            ->where('reminder_email', 'false')
            ->where('checkin', $date2)
            ->get();

        foreach ($bookings2 as $booking) {
            $sent += $this->sendReminder($booking, 'reminder_email');
        }

        // --- Scenario 2: 3-payment bookings, 1st done ---
        $days23 = (int) (\ModelHelper::getDataFromSetting('second_third_how_many_days') ?? 60);
        $date23 = Carbon::today()->addDays($days23)->toDateString();

        $bookings23 = BookingRequest::where('booking_status', 'booking-confirmed')
            ->where('booking_type_admin', 'invoice')
            ->where('total_payment', 3)
            ->where('how_many_payment_done', 1)
            ->where('third_reminder_email', 'false')
            ->where('reminder_email', 'false')
            ->where('checkin', $date23)
            ->get();

        foreach ($bookings23 as $booking) {
            $sent += $this->sendReminder($booking, 'reminder_email');
        }

        // --- Scenario 3: 3-payment bookings, 2nd done ---
        $days3 = (int) (\ModelHelper::getDataFromSetting('third_how_many_days') ?? 30);
        $date3 = Carbon::today()->addDays($days3)->toDateString();

        $bookings3 = BookingRequest::where('booking_status', 'booking-confirmed')
            ->where('booking_type_admin', 'invoice')
            ->where('total_payment', 3)
            ->where('how_many_payment_done', 2)
            ->where('third_reminder_email', 'false')
            ->where('checkin', $date3)
            ->get();

        foreach ($bookings3 as $booking) {
            $sent += $this->sendReminder($booking, 'third_reminder_email');
        }

        return $sent;
    }

    protected function sendReminder(BookingRequest $booking, string $flagColumn): int
    {
        try {
            $property = Property::find($booking->property_id);
            if (! $property) {
                return 0;
            }

            $data         = $booking->toArray();
            $adminTo      = \ModelHelper::getDataFromSetting('reminder_package_receiving_mail') ?? '';
            $adminSubject = 'Payment Reminder — ' . ($property->name ?? 'Property');
            $customerTo   = $booking->email;
            $customerSub  = 'Payment Reminder — ' . ($property->name ?? 'Property');

            $adminHtml = view('mail.reminder-admin-email', compact('property', 'data'))->render();
            $this->emailService->sendRenderedHtml($adminHtml, $adminTo, $adminSubject);

            $customerHtml = view('mail.reminder-user-email', compact('property', 'data'))->render();
            $this->emailService->sendRenderedHtml($customerHtml, $customerTo, $customerSub);

            BookingRequest::where('id', $booking->id)->update([$flagColumn => 'true']);

            return 1;
        } catch (\Throwable $e) {
            Log::error('Reminder email failed', [
                'booking_id' => $booking->id,
                'error'      => $e->getMessage(),
            ]);
            return 0;
        }
    }
}
