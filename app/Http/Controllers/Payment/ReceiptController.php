<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;

/**
 * ReceiptController — displays payment success / booking confirmation.
 *
 * Legacy: CommonController@showReceipt1 — shows booking confirmation after payment.
 */
class ReceiptController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService,
    ) {}

    /**
     * Show the post-payment success / confirmation page.
     *
     * Legacy: CommonController@showReceipt1 — resolves booking by ID, shows first-preview.
     */
    public function show(int $id)
    {
        $result = $this->paymentService->findForReceipt($id);

        if (! $result) {
            return abort(404);
        }

        $booking  = $result['booking'];
        $property = $result['property'];

        $data = (object) [
            'name'             => 'Booking Confirmation',
            'meta_title'       => 'Booking Confirmation',
            'meta_keywords'    => 'Booking Confirmation',
            'meta_description' => 'Booking Confirmation',
        ];

        $booking = $booking->toArray();

        return view('front.booking.payment.first-preview', compact('booking', 'data', 'property'));
    }
}
