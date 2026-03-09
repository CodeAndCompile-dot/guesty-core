<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BasicSetting;
use App\Models\BookingRequest;
use App\Models\Guesty\GuestyProperty;
use App\Models\Property;
use App\Services\Communication\EmailService;
use App\Services\Media\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * BookingController — public booking flow (Guesty inquiry, preview, rental agreement, payment).
 *
 * Legacy: PageController methods — saveBookingData, saveBookingData1, previewBooking,
 * rentalAggrementBooking, rentalAggrementDataSave, getQuoteAfter, updatepaymentBookingData,
 * checkAjaxGetQuoteData.
 */
class BookingController extends Controller
{
    public function __construct(
        protected EmailService $emailService,
        protected UploadService $uploadService,
    ) {}

    /* ------------------------------------------------------------------ */
    /*  Inquiry Booking (Guesty)                                           */
    /* ------------------------------------------------------------------ */

    /**
     * Create a Guesty inquiry booking and redirect to payment step.
     *
     * Legacy: PageController::saveBookingData
     */
    public function saveBookingData(Request $request)
    {
        $property = $this->resolveGuestyProperty($request->property_id);

        if (! $property) {
            return redirect()->back()->with('danger', 'Invalid Property');
        }

        $data         = $request->all();
        $data['name'] = ($request->firstname ?? '') . ' ' . ($request->lastname ?? '');

        // Upsert by request_id
        BookingRequest::where('request_id', $request->request_id)->delete();
        $booking = BookingRequest::create($data);

        // Parse fee breakdown
        $before            = json_decode($request->before_total_fees, true) ?? [];
        $fareAccommodation = 0;
        $fareCleaning      = 0;
        $invoiceItems      = [];

        foreach ($before as $b) {
            if (($b['type'] ?? '') === 'ACCOMMODATION_FARE') {
                $fareAccommodation += (float) ($b['amount'] ?? 0);
            } elseif (($b['type'] ?? '') === 'CLEANING_FEE') {
                $fareCleaning += (float) ($b['amount'] ?? 0);
            } elseif (($b['type'] ?? '') !== 'TAX') {
                $type = ($b['normalType'] ?? '') === 'AFE' ? 'DAMAGE_WAIVER' : ($b['type'] ?? '');
                $invoiceItems[] = [
                    'title'            => $b['title'] ?? '',
                    'amount'           => (float) ($b['amount'] ?? 0),
                    'normalType'       => $b['normalType'] ?? '',
                    'secondIdentifier' => $type,
                ];
            }
        }

        // Handle pet fees
        if ($request->total_pets && (int) $request->total_pets > 0) {
            $additionalFees = \GuestyApi::getAdditionalFeeDataAll($property->_id);

            if (! empty($additionalFees['data'])) {
                foreach ($additionalFees['data'] as $c) {
                    if (($c['type'] ?? '') === 'PET') {
                        $petFee = (float) ($c['value'] ?? 0);
                        $invoiceItems[] = [
                            'title'            => 'Pet Fee',
                            'amount'           => $petFee,
                            'normalType'       => 'PF',
                            'secondIdentifier' => 'PET',
                        ];
                        $booking->update(['pet_fee' => $petFee]);
                    }
                }
            }
        }

        // Create Guesty guest
        $guestResult = \GuestyApi::createGuest(
            $request->firstname,
            $request->lastname,
            $request->email,
            $request->mobile,
        );

        $guestId = $guestResult['data']['_id'] ?? null;

        if (! $guestId) {
            return redirect()->back()->with('danger', 'Failed to create guest in Guesty');
        }

        $booking->update([
            'new_guest_id'       => $guestId,
            'new_guest_object'   => json_encode($guestResult['data'] ?? []),
            'new_property_id'    => $property->_id,
        ]);

        // Build Guesty booking payload
        $guestCount = (int) $request->adults + (int) $request->child;
        $moneyJson  = $this->buildMoneyPayload($fareAccommodation, $fareCleaning, $invoiceItems);

        $payload = json_encode([
            'guestId'                 => $guestId,
            'listingId'               => $property->_id,
            'checkInDateLocalized'    => $request->checkin,
            'checkOutDateLocalized'   => $request->checkout,
            'status'                  => 'inquiry',
            'money'                   => $moneyJson,
            'guest'                   => [
                'firstName' => $request->firstname,
                'lastName'  => $request->lastname,
                'phone'     => $request->mobile,
                'email'     => $request->email,
            ],
        ]);

        $booking->update([
            'new_pre_booking_object' => $payload,
            'new_booking_status'     => 'inquiry',
        ]);

        // Create Guesty booking
        $response = \GuestyApi::newBookingCreate($payload);
        $reservationId = $response['data']['id'] ?? null;

        if ($reservationId) {
            $booking->update([
                'new_result_booking_object' => json_encode($response['data'] ?? []),
                'new_reservation_id'        => $reservationId,
            ]);

            return redirect('get-quote-after/' . $reservationId);
        }

        return redirect()->back()->with('danger', 'Booking creation failed');
    }

    /**
     * Create booking + immediate Guesty Pay tokenization + confirm.
     *
     * Legacy: PageController::saveBookingData1
     */
    public function saveBookingData1(Request $request)
    {
        $property = $this->resolveGuestyProperty($request->property_id);

        if (! $property) {
            return redirect()->back()->with('danger', 'Invalid Property');
        }

        $paymentId = $this->getGuestyPaymentId($property->_id);

        if (! $paymentId) {
            return redirect()->back()->with('danger', 'Payment is not defined in Guesty Account, please consult our team.');
        }

        if (! $request->checkin || ! $request->checkout) {
            return redirect()->back()->with('danger', ! $request->checkin ? 'Invalid Checkin' : 'Invalid Checkout');
        }

        $data         = $request->except(['_token', 'operation']);
        $data['name'] = ($request->firstname ?? '') . ' ' . ($request->lastname ?? '');

        // Tokenize card via Guesty Pay
        $tokenResult = $this->tokenizeCard($request, $paymentId, 'BOOKING-API-TOKEN-DATA');

        $paymentMethod = $tokenResult['data']['_id'] ?? null;

        if (! $paymentMethod) {
            return redirect()->back()->with('danger', 'Payment is not apply in Guesty Pay Account, please consult our team.');
        }

        $data['stripe_main_payment_method'] = $paymentMethod;

        // Upsert booking
        $existing = BookingRequest::where('request_id', $request->request_id)->first();

        if ($existing) {
            BookingRequest::where('request_id', $request->request_id)->update($data);
            $booking = BookingRequest::where('request_id', $request->request_id)->first();
        } else {
            $booking = BookingRequest::create($data);
        }

        // Submit to Guesty
        $result = \GuestyApi::setBookingDataNew(
            $request->firstname,
            $request->lastname,
            $booking->email,
            $booking->mobile,
            $booking->rate_api_id,
            $booking->stripe_main_payment_method,
            $booking->quote_id,
        );

        if (($result['status'] ?? 400) === 200) {
            $booking->update([
                'booking_status'          => 'booking-confirmed',
                'rental_aggrement_status' => 'true',
                'payment_status'          => 'Stripe key send',
                'booking_guesty_json'     => json_encode($result),
                'booking_guesty_id'       => $result['data']['_id'] ?? '',
            ]);

            return redirect('payment/success/' . $booking->id);
        }

        return redirect()->back()->with('danger', 'Error From Guesty API');
    }

    /* ------------------------------------------------------------------ */
    /*  Preview / Rental Agreement                                         */
    /* ------------------------------------------------------------------ */

    /**
     * Preview a booking before payment.
     *
     * Legacy: PageController::previewBooking
     */
    public function previewBooking(Request $request, int $id)
    {
        $booking = BookingRequest::find($id);

        if (! $booking) {
            abort(404);
        }

        $property = GuestyProperty::find($booking->property_id);

        if (! $property) {
            abort(404);
        }

        $data = (object) [
            'name'             => 'Booking Request',
            'meta_title'       => 'Booking Request',
            'meta_keywords'    => 'Booking Request',
            'meta_description' => 'Booking Request',
        ];

        $booking = $booking->toArray();

        return view('front.booking.preview', compact('booking', 'data', 'property'));
    }

    /**
     * Rental agreement form.
     *
     * Legacy: PageController::rentalAggrementBooking
     */
    public function rentalAggrementBooking(Request $request, int $id)
    {
        $booking = BookingRequest::find($id);

        if (! $booking) {
            abort(404);
        }

        if ($booking->rental_aggrement_status === 'true') {
            return redirect()->to('booking/payment/paypal/' . $booking->id)
                ->with('danger', 'Rental Agreement already submitted');
        }

        $property = GuestyProperty::find($booking->property_id);

        if (! $property) {
            abort(404);
        }

        $data = (object) [
            'name'             => 'Rental Agreement',
            'meta_title'       => 'Rental Agreement',
            'meta_keywords'    => 'Rental Agreement',
            'meta_description' => 'Rental Agreement',
        ];

        $booking = $booking->toArray();

        return view('front.booking.rentalAggrementBooking', compact('booking', 'data', 'property'));
    }

    /**
     * Save rental agreement (signature + images) and redirect to payment.
     *
     * Legacy: PageController::rentalAggrementDataSave
     * Note: Legacy had a bug — rendered email HTML but never sent it. We fix this by actually sending.
     */
    public function rentalAggrementDataSave(Request $request)
    {
        if (! $request->booking_id) {
            abort(404);
        }

        $booking = BookingRequest::find($request->booking_id);

        if (! $booking) {
            abort(404);
        }

        if ($booking->rental_aggrement_status === 'true') {
            return redirect()->to('get-quote-after/' . $booking->new_reservation_id)
                ->with('danger', 'Rental Agreement already submitted');
        }

        $property = GuestyProperty::find($booking->property_id);

        if (! $property) {
            abort(404);
        }

        // Save signature image from base64
        if ($request->signature) {
            $pngUrl = 'signature-' . time() . '.png';
            $path   = public_path('uploads/signature/' . $pngUrl);
            $dir    = dirname($path);

            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            // Decode base64 signature data (data:image/png;base64,…)
            $signatureData = $request->signature;

            if (str_contains($signatureData, ',')) {
                $signatureData = explode(',', $signatureData, 2)[1];
            }

            file_put_contents($path, base64_decode($signatureData));
            $booking->rental_aggrement_signature = 'uploads/signature/' . $pngUrl;
        }

        if ($request->hasFile('image')) {
            $booking->rental_aggrement_images = $this->uploadService->upload($request->file('image'), 'cms');
        }

        $booking->rental_aggrement_status = 'true';
        $booking->rental_agreement_link   = $property->rental_aggrement_attachment ?? '';
        $booking->booking_status          = 'rental-aggrement-success';
        $booking->save();

        // Send rental agreement email to admin (fix legacy bug: send was missing)
        try {
            $data     = $booking->fresh()->toArray();
            $adminTo  = \ModelHelper::getDataFromSetting('rental_aggrement_receiving_mail') ?? '';
            $subject  = 'Rental Agreement in ' . ($property->name ?? $property->title ?? '');

            if ($adminTo) {
                $this->emailService->sendFromView(
                    'mail.rental-aggrement-admin',
                    compact('data', 'property'),
                    $adminTo,
                    $subject,
                );
            }
        } catch (\Throwable $e) {
            Log::warning('Rental agreement email failed', ['error' => $e->getMessage()]);
        }

        return redirect('get-quote-after/' . $booking->new_reservation_id);
    }

    /* ------------------------------------------------------------------ */
    /*  Post-inquiry payment step                                          */
    /* ------------------------------------------------------------------ */

    /**
     * Show the payment form for an inquiry booking.
     *
     * Legacy: PageController::getQuoteAfter
     */
    public function getQuoteAfter(string $reservationId)
    {
        $booking = BookingRequest::where('new_reservation_id', $reservationId)
            ->where('new_booking_status', 'inquiry')
            ->first();

        if (! $booking) {
            abort(404);
        }

        $property = GuestyProperty::find($booking->property_id);

        if (! $property) {
            abort(404);
        }

        $data = (object) [
            'name'             => 'Payment Request',
            'meta_title'       => 'Payment Request',
            'meta_keywords'    => 'Payment Request',
            'meta_description' => 'Payment Request',
        ];

        return view('front.booking.second-step-get-quote', compact('booking', 'data', 'property'));
    }

    /**
     * Process payment for an inquiry booking (Guesty Pay tokenize + confirm).
     *
     * Legacy: PageController::updatepaymentBookingData
     */
    public function updatepaymentBookingData(string $reservationId, Request $request)
    {
        $booking = BookingRequest::where('new_reservation_id', $reservationId)
            ->where('new_booking_status', 'inquiry')
            ->first();

        if (! $booking) {
            abort(404);
        }

        $property = GuestyProperty::find($booking->property_id);

        if (! $property) {
            abort(404);
        }

        // Update total amount
        BookingRequest::where('new_reservation_id', $reservationId)
            ->where('new_booking_status', 'inquiry')
            ->update(['total_amount' => $request->total_amount]);

        $booking = $booking->fresh();

        $paymentId = $this->getGuestyPaymentId($property->_id);

        if (! $paymentId) {
            return redirect()->back()->with('danger', 'Payment is not defined in Guesty Account');
        }

        // Tokenize card
        $tokenResult = $this->tokenizeCard($request, $paymentId, 'API-TOKEN-DATA');

        $paymentMethod = $tokenResult['data']['_id'] ?? null;

        if (! $paymentMethod) {
            $errorMessage = $tokenResult['error']['raw']['message']
                ?? $tokenResult['error']['message']
                ?? 'Payment tokenization failed';

            return redirect()->back()->with('danger', $errorMessage);
        }

        // Confirm booking + attach payment in Guesty
        \GuestyApi::confirmBooking($booking->new_reservation_id);
        \GuestyApi::paymentAttached(
            $booking->new_guest_id,
            $paymentId,
            $paymentMethod,
            $booking->new_reservation_id,
        );

        $booking->update([
            'new_booking_status'      => 'confirm',
            'booking_status'          => 'booking-confirmed',
            'rental_aggrement_status' => 'true',
            'payment_status'          => 'Stripe key send',
        ]);

        return redirect('payment/success/' . $booking->id);
    }

    /* ------------------------------------------------------------------ */
    /*  AJAX: Get quote pricing                                            */
    /* ------------------------------------------------------------------ */

    /**
     * AJAX: Fetch live pricing for a property.
     *
     * Legacy: PageController::checkAjaxGetQuoteData
     */
    public function checkAjaxGetQuoteData(Request $request)
    {
        if (! $request->property_id) {
            return response()->json(['message' => 'Property Not selected', 'status' => 400]);
        }

        $property = GuestyProperty::find($request->property_id);

        if (! $property) {
            return response()->json(['message' => 'Invalid Property', 'status' => 400]);
        }

        if (! $request->start_date) {
            return response()->json(['message' => 'Invalid Checkin', 'status' => 400]);
        }

        if (! $request->end_date) {
            return response()->json(['message' => 'Invalid Checkout', 'status' => 400]);
        }

        $guestCount = (int) ($request->adults ?? 0) + (int) ($request->childs ?? 0);
        $coupon     = $request->get('coupon', 'default') ?: 'default';

        // Day-level availability check
        $checkResult = \Helper::getGrossDataCheckerDays($property, $request->start_date, $request->end_date);

        if ($checkResult && ($checkResult['status'] ?? 200) !== 200) {
            return response()->json(['message' => $checkResult['message'] ?? 'Not available', 'status' => 400]);
        }

        // Get quote from Guesty
        $guestyApi = \GuestyApi::getQuouteNewNew(
            $guestCount,
            $request->start_date,
            $request->end_date,
            $property->_id,
            $coupon,
        );

        if (! $guestyApi || ($guestyApi['status'] ?? 400) !== 200) {
            return response()->json([
                'message' => $guestyApi['message'] ?? 'Something happened',
                'status'  => 400,
            ]);
        }

        $mainData = [
            'guestyapi'    => $guestyApi,
            'total_guests' => $guestCount,
            'adults'       => $request->get('adults'),
            'child'        => $request->get('childs'),
            'childs'       => $request->get('childs'),
            'start_date'   => $request->get('start_date'),
            'end_date'     => $request->get('end_date'),
        ];

        $dataView = view('front.property.ajax-gaurav-data-get-quote', compact('property', 'mainData'))->render();

        return response()->json(['message' => 'success', 'status' => 200, 'data_view' => $dataView]);
    }

    /* ------------------------------------------------------------------ */
    /*  Private helpers                                                     */
    /* ------------------------------------------------------------------ */

    private function resolveGuestyProperty(?int $id): ?GuestyProperty
    {
        return $id ? GuestyProperty::find($id) : null;
    }

    /**
     * Get Guesty payment provider ID for a property.
     */
    private function getGuestyPaymentId(string $guestyPropertyId): ?string
    {
        $result = \GuestyApi::getBookingPaymentid($guestyPropertyId);

        return $result['data']['_id'] ?? null;
    }

    /**
     * Tokenize a card via Guesty Pay API.
     */
    private function tokenizeCard(Request $request, string $paymentProviderId, string $tokenSettingKey): array
    {
        $token = BasicSetting::where('name', $tokenSettingKey)->value('value');

        if (! $token) {
            return ['status' => 400, 'error' => ['message' => 'API token not configured']];
        }

        $amount = $request->total_amount ?? 0;

        $payload = json_encode([
            'paymentProviderId' => $paymentProviderId,
            'card' => [
                'number'    => $request->card_number,
                'exp_month' => $request->card_expiry_month,
                'exp_year'  => $request->card_expiry_year,
                'cvc'       => $request->card_cvv,
            ],
            'billing_details' => [
                'name'    => $request->card_name ?? (($request->firstname ?? '') . ' ' . ($request->lastname ?? '')),
                'address' => [
                    'line1'       => $request->address_line_1 ?? '',
                    'city'        => $request->city ?? '',
                    'postal_code' => $request->zipcode ?? '',
                    'country'     => $request->country ?? '',
                ],
            ],
            'threeDS' => [
                'amount'     => (float) $amount,
                'currency'   => 'USD',
                'successURL' => url('thankyou'),
                'failureURL' => url('failed'),
            ],
        ]);

        try {
            $client   = new \GuzzleHttp\Client();
            $response = $client->request('POST', 'https://pay.guesty.com/api/tokenize/v2', [
                'body'    => $payload,
                'headers' => [
                    'accept'        => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                    'content-type'  => 'application/json',
                ],
            ]);

            $responseData = json_decode($response->getBody(), true);

            return ['status' => 200, 'message' => 'success', 'data' => $responseData];
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $body = json_decode((string) $e->getResponse()->getBody(), true);
            Log::error('Guesty Pay tokenization failed', ['error' => $body]);

            return ['status' => 401, 'error' => $body ?? ['message' => 'Tokenization failed']];
        } catch (\Throwable $e) {
            Log::error('Guesty Pay tokenization error', ['error' => $e->getMessage()]);

            return ['status' => 400, 'error' => ['message' => $e->getMessage()]];
        }
    }

    /**
     * Build the "money" section of the Guesty booking payload.
     */
    private function buildMoneyPayload(float $fareAccommodation, float $fareCleaning, array $invoiceItems): array
    {
        $money = [
            'fareAccommodation' => (string) $fareAccommodation,
            'fareCleaning'      => (string) $fareCleaning,
            'currency'          => 'USD',
        ];

        if (! empty($invoiceItems)) {
            $money['invoiceItems'] = $invoiceItems;
        }

        return $money;
    }
}
