<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\BookingRequest;
use App\Models\Property;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;

/**
 * PaypalController — handles PayPal payment form display and client-side capture recording.
 *
 * Legacy: Payment\PaypalController (client-side PayPal SDK, no server-side verification)
 */
class PaypalController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService,
    ) {}

    /**
     * Show the PayPal payment page for a booking.
     * Legacy: PaypalController@index — redirects to Stripe if that gateway is configured.
     */
    public function index(Request $request, int $id)
    {
        // Legacy: if gateway is stripe, redirect to stripe payment page
        if (\ModelHelper::getDataFromSetting('which_payment_gateway') === 'stripe') {
            return redirect()->route('stripe_payment', $id);
        }

        $booking = BookingRequest::find($id);
        if (! $booking) {
            return abort(404);
        }

        $property = Property::find($booking->property_id);
        if (! $property) {
            return abort(404);
        }

        $data = (object) [
            'name'             => 'Payment Request',
            'meta_title'       => 'Payment Request',
            'meta_keywords'    => 'Payment Request',
            'meta_description' => 'Payment Request',
        ];

        $booking = $booking->toArray();

        return view('front.booking.payment.paypal', compact('booking', 'data', 'property'));
    }

    /**
     * Record a PayPal payment after client-side capture.
     * Legacy: PaypalController@indexPost — called via redirect with query params.
     */
    public function verify(Request $request, int $id)
    {
        $result = $this->paymentService->recordPaypal(
            $id,
            $request->input('tx', ''),
            $request->input('st', ''),
            (float) $request->input('amt', 0),
        );

        if ($result['success'] && $result['payment']) {
            return redirect('payment/success/' . $id)
                ->with('success', 'Payment successful');
        }

        return redirect()->back()->with('danger', $result['message']);
    }
}
