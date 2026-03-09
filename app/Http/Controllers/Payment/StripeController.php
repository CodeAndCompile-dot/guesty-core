<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\StripePaymentRequest;
use App\Models\BookingRequest;
use App\Models\GuestyProperty;
use App\Services\Payment\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * StripeController — handles Stripe payment form display and charge processing.
 *
 * Legacy: Payment\StripeController (Stripe.js v2 tokenization + Charge API)
 */
class StripeController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService,
    ) {}

    /**
     * Show the Stripe payment form for a booking.
     * Legacy: StripeController@index
     */
    public function index(int $id)
    {
        $booking = BookingRequest::find($id);

        if (! $booking) {
            return abort(404);
        }

        $property = GuestyProperty::find($booking->property_id);

        if (! $property) {
            return abort(404);
        }

        $data = (object) [
            'name'             => 'Stripe Payment Booking',
            'meta_title'       => 'Stripe Payment Booking',
            'meta_keywords'    => 'Stripe Payment Booking',
            'meta_description' => 'Stripe Payment Booking',
        ];

        $booking = $booking->toArray();

        return view('front.booking.payment.stripe', compact('booking', 'data', 'property'));
    }

    /**
     * Process a Stripe charge.
     * Legacy: StripeController@indexPost
     */
    public function store(StripePaymentRequest $request, int $id)
    {
        $result = $this->paymentService->chargeStripe(
            $id,
            $request->input('stripeToken'),
            (float) $request->input('amount'),
        );

        if ($result['success'] && $result['payment']) {
            return redirect('payment/success/' . $id)
                ->with('success', 'Payment successful');
        }

        return redirect()->back()->with('danger', $result['message']);
    }

    /**
     * Create a SetupIntent for card saving.
     * Legacy: StripeController@getIntendentData
     */
    public function getIntentData(): JsonResponse
    {
        $result = $this->paymentService->createSetupIntent();

        return response()->json($result);
    }

    /**
     * Create a PaymentIntent for a given amount.
     * Legacy: StripeController@payment_init
     */
    public function paymentInit(Request $request): JsonResponse
    {
        $amount = (float) $request->input('total_amount', 0);

        $result = $this->paymentService->createPaymentIntent($amount);

        return response()->json($result);
    }
}
