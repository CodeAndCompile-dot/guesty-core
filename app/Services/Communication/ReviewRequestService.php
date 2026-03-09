<?php

namespace App\Services\Communication;

use App\Models\BookingRequest;
use App\Models\Property;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * ReviewRequestService — sends review-request emails X days after check-out.
 *
 * Legacy: ICalController::sendReviewEmail() / sendReviewEmail1()
 */
class ReviewRequestService
{
    public function __construct(
        protected EmailService $emailService,
    ) {}

    /**
     * @return int  Number of review requests sent
     */
    public function process(): int
    {
        $daysAfter = (int) (\ModelHelper::getDataFromSetting('review_send_day') ?? 3);
        $date      = Carbon::today()->subDays($daysAfter)->toDateString();
        $sent      = 0;

        $bookings = BookingRequest::where('booking_status', 'booking-confirmed')
            ->where('review_email', 'false')
            ->where('booking_type_admin', 'invoice')
            ->where('checkout', $date)
            ->get();

        foreach ($bookings as $booking) {
            try {
                $property = Property::find($booking->property_id);
                if (! $property) {
                    continue;
                }

                $data         = $booking->toArray();
                $adminTo      = \ModelHelper::getDataFromSetting('review_receiving_mail') ?? '';
                $adminSubject = 'Review Request — ' . ($property->name ?? 'Property');
                $customerTo   = $booking->email;
                $customerSub  = "We'd love your review - " . ($property->name ?? 'Property');

                $adminHtml = view('mail.review-admin', compact('property', 'data'))->render();
                $this->emailService->sendRenderedHtml($adminHtml, $adminTo, $adminSubject);

                $customerHtml = view('mail.review-customer', compact('property', 'data'))->render();
                $this->emailService->sendRenderedHtml($customerHtml, $customerTo, $customerSub);

                BookingRequest::where('id', $booking->id)->update(['review_email' => 'true']);
                $sent++;
            } catch (\Throwable $e) {
                Log::error('Review request email failed', [
                    'booking_id' => $booking->id,
                    'error'      => $e->getMessage(),
                ]);
            }
        }

        return $sent;
    }
}
