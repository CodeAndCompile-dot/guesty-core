<?php

namespace App\Services\Payment;

use App\Models\BookingRequest;
use App\Models\GuestyProperty;
use App\Models\Payment;
use App\Models\Property;
use App\Services\Calendar\ICalService;
use App\Services\Communication\EmailService;
use Illuminate\Support\Facades\Log;

/**
 * PaymentService — handles Stripe and PayPal payment processing,
 * recording transactions, and post-payment booking updates.
 *
 * Legacy: Logic was split across StripeController, PaypalController,
 * CommonController and ModelHelper::finalEmailAndUpdateBookingPayment.
 */
class PaymentService
{
    public function __construct(
        protected StripeGateway $stripeGateway,
        protected ICalService $icalService,
        protected EmailService $emailService,
    ) {}

    /* ------------------------------------------------------------------ */
    /*  Stripe                                                             */
    /* ------------------------------------------------------------------ */

    /**
     * Create a Stripe Charge for a booking.
     *
     * @return array{success: bool, payment: Payment|null, message: string}
     */
    public function chargeStripe(int $bookingId, string $stripeToken, float $amount): array
    {
        $booking = BookingRequest::find($bookingId);
        if (! $booking) {
            return ['success' => false, 'payment' => null, 'message' => 'Booking is invalid'];
        }

        $property = GuestyProperty::find($booking->property_id)
            ?? Property::find($booking->property_id);

        if (! $property) {
            return ['success' => false, 'payment' => null, 'message' => 'Property is no longer available'];
        }

        $propertyName = $property->title ?? $property->name ?? 'Property';

        try {
            $charge = $this->stripeGateway->createCharge(
                $booking->email,
                $stripeToken,
                $amount,
                "Payment for {$propertyName}",
            );

            $chargeJson = $charge->jsonSerialize();

            if ($chargeJson['amount_refunded'] == 0
                && empty($chargeJson['failure_code'])
                && $chargeJson['paid'] == 1
                && $chargeJson['captured'] == 1
            ) {
                $payment = Payment::create([
                    'booking_id'          => $booking->id,
                    'receipt_url'         => $chargeJson['receipt_url'] ?? '',
                    'customer_id'         => $chargeJson['customer'] ?? '',
                    'balance_transaction' => $chargeJson['balance_transaction'] ?? '',
                    'tran_id'             => $chargeJson['id'] ?? '',
                    'description'         => json_encode($chargeJson),
                    'status'              => 'complete',
                    'type'                => 'stripe',
                    'amount'              => $amount,
                ]);

                $this->finalisePayment($bookingId, $booking, $payment, $property);

                return ['success' => true, 'payment' => $payment, 'message' => 'Payment successful'];
            }

            return ['success' => false, 'payment' => null, 'message' => 'Payment was not captured'];
        } catch (\Throwable $e) {
            Log::error('Stripe charge failed', ['booking_id' => $bookingId, 'error' => $e->getMessage()]);

            return ['success' => false, 'payment' => null, 'message' => $e->getMessage()];
        }
    }

    /**
     * Create a Stripe SetupIntent (for saving card details for future charges).
     *
     * @return array{status: int, message: string, stripe_object: mixed}
     */
    public function createSetupIntent(): array
    {
        try {
            $intent = $this->stripeGateway->createSetupIntent();

            return ['status' => 200, 'message' => 'success', 'stripe_object' => $intent];
        } catch (\Throwable $e) {
            Log::error('Stripe setupIntent failed', ['error' => $e->getMessage()]);

            return ['status' => 400, 'message' => $e->getMessage(), 'stripe_object' => ''];
        }
    }

    /**
     * Create a Stripe PaymentIntent for a given amount.
     *
     * @return array{id: string, clientSecret: string}|array{error: string}
     */
    public function createPaymentIntent(float $amount): array
    {
        try {
            $intent = $this->stripeGateway->createPaymentIntent($amount);

            return ['id' => $intent->id, 'clientSecret' => $intent->client_secret];
        } catch (\Throwable $e) {
            Log::error('Stripe paymentIntent failed', ['error' => $e->getMessage()]);

            return ['error' => $e->getMessage()];
        }
    }

    /* ------------------------------------------------------------------ */
    /*  PayPal                                                             */
    /* ------------------------------------------------------------------ */

    /**
     * Record a PayPal payment after client-side capture.
     *
     * @return array{success: bool, payment: Payment|null, message: string}
     */
    public function recordPaypal(int $bookingId, string $transactionId, string $status, float $amount): array
    {
        $booking = BookingRequest::find($bookingId);
        if (! $booking) {
            return ['success' => false, 'payment' => null, 'message' => 'Booking is invalid'];
        }

        $property = Property::find($booking->property_id);
        if (! $property) {
            return ['success' => false, 'payment' => null, 'message' => 'Property is no longer available'];
        }

        if (strtoupper($status) !== 'COMPLETED') {
            return ['success' => false, 'payment' => null, 'message' => 'Payment was not completed'];
        }

        $payment = Payment::create([
            'booking_id'  => $booking->id,
            'receipt_url' => '',
            'customer_id' => '',
            'amount'      => $amount,
            'tran_id'     => $transactionId,
            'description' => json_encode(compact('transactionId', 'status', 'amount')),
            'type'        => 'paypal',
            'status'      => 'complete',
        ]);

        $this->finalisePayment($bookingId, $booking, $payment, $property);

        return ['success' => true, 'payment' => $payment, 'message' => 'Payment successful'];
    }

    /* ------------------------------------------------------------------ */
    /*  Receipt / Lookup                                                   */
    /* ------------------------------------------------------------------ */

    /**
     * Find a booking with its property for receipt display.
     *
     * @return array{booking: BookingRequest, property: object}|null
     */
    public function findForReceipt(int $bookingId): ?array
    {
        $booking = BookingRequest::find($bookingId);
        if (! $booking) {
            return null;
        }

        $property = GuestyProperty::find($booking->property_id)
            ?? Property::find($booking->property_id);
        if (! $property) {
            return null;
        }

        return ['booking' => $booking, 'property' => $property];
    }

    /**
     * Resolve the effective payment amount (handles discounts).
     */
    public function resolveAmount(array|BookingRequest $booking): float
    {
        $arr = $booking instanceof BookingRequest ? $booking->toArray() : $booking;

        $amount = (float) ($arr['total_amount'] ?? 0);

        if (! empty($arr['discount']) && $arr['discount'] != 0) {
            $amount = (float) ($arr['after_discount_total'] ?? $amount);
        }

        return round($amount, 2);
    }

    /* ------------------------------------------------------------------ */
    /*  Post-payment: update booking, refresh iCal, send emails            */
    /* ------------------------------------------------------------------ */

    /**
     * Finalise a payment: mark installment, update booking status, refresh iCal.
     *
     * Legacy: ModelHelper::finalEmailAndUpdateBookingPayment()
     *
     * Email sending is deferred to Phase 9.
     */
    public function finalisePayment(int $bookingId, BookingRequest $booking, Payment $payment, object $property): void
    {
        // --- Update instalment tracking in amount_data JSON ---
        $newAmountData = [];
        $statusPayment = 'partially';

        if ($booking->amount_data) {
            $amountData = json_decode($booking->amount_data, true);

            if (is_array($amountData)) {
                $updated = false;

                foreach ($amountData as $entry) {
                    $item = $entry;

                    if (! isset($entry['status']) && ! $updated) {
                        if ((float) trim($entry['amount']) == (float) $payment->amount) {
                            $item['status']  = 'complete';
                            $item['tran_id'] = $payment->tran_id;
                            $item['mode']    = $payment->type;
                            $item['date']    = now()->format('Y-m-d H:i:s');
                            $updated = true;
                        }
                    }

                    $newAmountData[] = $item;
                }
            }
        }

        // Determine if all instalments are paid
        $paid  = 0;
        $total = 0;
        foreach ($newAmountData as $entry) {
            if (isset($entry['status'])) {
                $paid++;
            }
            $total++;
        }

        if ($total > 0 && $paid === $total) {
            $statusPayment = 'paid';
        }

        // Update booking
        BookingRequest::where('id', $bookingId)->update([
            'booking_status'       => 'booking-confirmed',
            'payment_status'       => $statusPayment,
            'amount_data'          => json_encode($newAmountData),
            'how_many_payment_done' => $paid,
        ]);

        // Refresh iCal for this property
        try {
            $propertyId = $booking->property_id;
            $imports = \App\Models\IcalImportList::where('property_id', $propertyId)->get();

            foreach ($imports as $import) {
                $this->icalService->refreshImport($propertyId, $import->ical_link, $import->id);
            }
        } catch (\Throwable $e) {
            Log::warning('iCal refresh after payment failed', ['error' => $e->getMessage()]);
        }

        // Send payment confirmation emails
        $this->sendPaymentEmails($booking->fresh(), $property);
    }

    /**
     * Send payment-confirmation emails to admin and customer.
     */
    protected function sendPaymentEmails(BookingRequest $booking, object $property): void
    {
        try {
            $data    = $booking->toArray();
            $subject = 'Payment Received — ' . ($property->name ?? $property->title ?? 'Property');

            // Collect attachment if property document exists
            $files = [];
            $propDoc = $property->property_document ?? null;
            if ($propDoc && file_exists(public_path('uploads/properties/' . $propDoc))) {
                $files[] = public_path('uploads/properties/' . $propDoc);
            }

            // Admin email
            $adminTo = \ModelHelper::getDataFromSetting('payment_receiving_mail') ?? '';
            if ($adminTo) {
                $adminHtml = view('mail.booking-first-admin', compact('data', 'property'))->render();
                $this->emailService->sendRenderedHtml($adminHtml, $adminTo, $subject, $files);
            }

            // Customer email
            if (! empty($booking->email)) {
                $customerHtml = view('mail.booking-first-customer', compact('data', 'property'))->render();
                $this->emailService->sendRenderedHtml($customerHtml, $booking->email, $subject, $files);
            }
        } catch (\Throwable $e) {
            Log::error('Payment confirmation email failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
