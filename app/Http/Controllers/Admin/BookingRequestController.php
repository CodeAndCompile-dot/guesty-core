<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequestFormRequest;
use App\Services\BookingService;
use App\Services\Calendar\AvailabilityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Admin controller for Booking Enquiries.
 *
 * Thin controller — all business logic delegated to BookingService.
 * Preserves every legacy route name and redirect target.
 */
class BookingRequestController extends Controller
{
    protected string $adminView = 'admin.booking-enquiries';

    protected string $adminBaseUrl = 'booking-enquiries.index';

    public function __construct(
        protected BookingService $bookingService,
        protected AvailabilityService $availabilityService,
    ) {}

    /* ------------------------------------------------------------------ */
    /*  AJAX: calendar availability data                                   */
    /* ------------------------------------------------------------------ */

    /**
     * POST get-checkin-checkout-data-gaurav
     * Returns JSON {checkin: [...], checkout: [...]} for datepicker.
     */
    public function getCheckinCheckoutDataGaurav(Request $request): JsonResponse
    {
        $data = $this->availabilityService->getCheckInCheckOut((int) $request->input('id'));

        return response()->json($data);
    }

    /* ------------------------------------------------------------------ */
    /*  Index (all bookings)                                               */
    /* ------------------------------------------------------------------ */

    public function index()
    {
        $data = $this->bookingService->listAll();

        return view($this->adminView.'.index', compact('data'));
    }

    /* ------------------------------------------------------------------ */
    /*  Single property bookings                                           */
    /* ------------------------------------------------------------------ */

    /**
     * GET booking-enquiries/properties/{id}  (route: singlePropertyBookoing)
     */
    public function singlePropertyBookoing(int $id)
    {
        $data = $this->bookingService->listForProperty($id);

        if ($data === null) {
            return abort(404);
        }

        return view($this->adminView.'.show', compact('data'));
    }

    /* ------------------------------------------------------------------ */
    /*  Create / Store                                                     */
    /* ------------------------------------------------------------------ */

    public function create()
    {
        return view($this->adminView.'.create');
    }

    public function store(BookingRequestFormRequest $request)
    {
        $booking = $this->bookingService->store($request->validated());

        // Non-invoice (manual) bookings go straight to the list
        if ($request->input('booking_type_admin') !== 'invoice') {
            return redirect()->route($this->adminBaseUrl)
                ->with('success', 'Successfully Added');
        }

        // Invoice bookings redirect to the confirm flow
        return redirect()->route('booking-enquiry-confirm', $booking->id)
            ->with('success', 'Successfully Added');
    }

    /* ------------------------------------------------------------------ */
    /*  Show (redirect to index — legacy behavior)                         */
    /* ------------------------------------------------------------------ */

    public function show(int $id)
    {
        return redirect()->route($this->adminBaseUrl);
    }

    /* ------------------------------------------------------------------ */
    /*  Edit / Update                                                      */
    /* ------------------------------------------------------------------ */

    public function edit(int $id)
    {
        $data = $this->bookingService->find($id);

        if (! $data) {
            return redirect()->route($this->adminBaseUrl)
                ->with('danger', 'Invalid Calling');
        }

        return view($this->adminView.'.edit', compact('data'));
    }

    public function update(BookingRequestFormRequest $request, int $id)
    {
        $booking = $this->bookingService->update($id, $request->validated());

        if (! $booking) {
            return redirect()->route($this->adminBaseUrl)
                ->with('danger', 'Invalid Calling');
        }

        return redirect()->route('singlePropertyBookoing', $booking->property_id)
            ->with('success', 'Successfully Updated');
    }

    /* ------------------------------------------------------------------ */
    /*  Destroy (cancel booking)                                           */
    /* ------------------------------------------------------------------ */

    public function destroy(int $id)
    {
        $result = $this->bookingService->cancel($id);

        if (! $result['success']) {
            return redirect()->route($this->adminBaseUrl)
                ->with('danger', $result['message']);
        }

        return redirect()->route('singlePropertyBookoing', $result['property_id'])
            ->with('success', 'Successfully Deleted');
    }

    /* ------------------------------------------------------------------ */
    /*  Confirmed (send payment request email, advance status)             */
    /* ------------------------------------------------------------------ */

    /**
     * GET booking-enquiries/confirmed/{id}  (route: booking-enquiry-confirm)
     */
    public function confirmed(int $id)
    {
        $result = $this->bookingService->confirm($id);

        if (! $result['success']) {
            return redirect()->route($this->adminBaseUrl)
                ->with('danger', $result['message']);
        }

        return redirect()->route('singlePropertyBookoing', $result['property_id'])
            ->with('success', 'Successfully send');
    }

    /* ------------------------------------------------------------------ */
    /*  AJAX: Admin quote calculation                                      */
    /* ------------------------------------------------------------------ */

    /**
     * POST admin-checkajax-get-quote
     *
     * Legacy: PageController::adminCheckAjaxGetQuoteData()
     * Uses Helper::getGrossAmountData() for non-Guesty Property rates.
     */
    public function adminCheckAjaxGetQuoteData(Request $request): JsonResponse
    {
        if (! $request->property_id) {
            return response()->json(['message' => 'Property Not select', 'status' => 400]);
        }

        $property = \App\Models\Property::find($request->property_id);

        if (! $request->start_date) {
            return response()->json(['message' => 'Invalid Checkin', 'status' => 400]);
        }

        if (! $request->end_date) {
            return response()->json(['message' => 'Invalid Checkout', 'status' => 400]);
        }

        $main_data = \Helper::getGrossAmountData($property, $request->start_date, $request->end_date);

        if ($main_data['status'] === true || $main_data['status'] === 'true') {
            $main_data['start_date']           = $request->get('start_date');
            $main_data['end_date']             = $request->get('end_date');
            $main_data['adults']               = $request->get('adults');
            $main_data['child']                = $request->get('childs');
            $main_data['childs']               = $request->get('childs');
            $main_data['pet_fee_data_guarav']  = $request->get('pet_fee_data_guarav');
            $main_data['heating_pool_fee']     = $request->get('heating_pool_fee_data_guarav');
            $main_data['total_guests']         = (int) $request->get('adults') + (int) $request->get('childs');
            $main_data['extra_discount']       = $request->get('extra_discount');

            $data_view = view('admin.common.get-quote', compact('main_data', 'property'))->render();

            return response()->json(['message' => 'success', 'status' => 200, 'data_view' => $data_view]);
        }

        if ($main_data['status'] === 'already-booked') {
            return response()->json(['message' => 'Already booked some date', 'status' => 400]);
        }

        if ($main_data['status'] === 'checkin-checkout-day') {
            return response()->json(['message' => $main_data['message'], 'status' => 400]);
        }

        if ($main_data['status'] === 'min-stay-day') {
            return response()->json(['message' => 'Minimum stay is not statisfy', 'status' => 400]);
        }

        if ($main_data['status'] === 'date-price') {
            return response()->json(['message' => 'Price is not defined', 'status' => 400]);
        }

        return response()->json(['message' => 'Invalid Calling', 'status' => 400, 'message1' => $main_data['status']]);
    }

    /**
     * POST admin-checkajax-get-quote-edit
     *
     * Legacy: PageController::adminCheckAjaxGetQuoteDataEdit()
     * Same as adminCheckAjaxGetQuoteData but renders the edit version and includes coupon fields.
     */
    public function adminCheckAjaxGetQuoteDataEdit(Request $request): JsonResponse
    {
        if (! $request->property_id) {
            return response()->json(['message' => 'Property Not select', 'status' => 400]);
        }

        $property = \App\Models\Property::find($request->property_id);

        if (! $request->start_date) {
            return response()->json(['message' => 'Invalid Checkin', 'status' => 400]);
        }

        if (! $request->end_date) {
            return response()->json(['message' => 'Invalid Checkout', 'status' => 400]);
        }

        $main_data = \Helper::getGrossAmountData($property, $request->start_date, $request->end_date);

        if ($main_data['status'] === true || $main_data['status'] === 'true') {
            $main_data['start_date']             = $request->get('start_date');
            $main_data['end_date']               = $request->get('end_date');
            $main_data['adults']                 = $request->get('adults');
            $main_data['child']                  = $request->get('childs');
            $main_data['childs']                 = $request->get('childs');
            $main_data['pet_fee_data_guarav']    = $request->get('pet_fee_data_guarav');
            $main_data['heating_pool_fee']       = $request->get('heating_pool_fee_data_guarav');
            $main_data['total_guests']           = (int) $request->get('adults') + (int) $request->get('childs');
            $main_data['extra_discount']         = $request->get('extra_discount');
            $main_data['coupon_discount']        = $request->get('coupon_discount');
            $main_data['coupon_discount_code']   = $request->get('coupon_discount_code');

            $data_view = view('admin.common.get-quote-edit', compact('main_data', 'property'))->render();

            return response()->json(['message' => 'success', 'status' => 200, 'data_view' => $data_view]);
        }

        if ($main_data['status'] === 'already-booked') {
            return response()->json(['message' => 'Already booked some date', 'status' => 400]);
        }

        if ($main_data['status'] === 'min-stay-day') {
            return response()->json(['message' => 'Minimum stay is not statisfy', 'status' => 400]);
        }

        if ($main_data['status'] === 'date-price') {
            return response()->json(['message' => 'Price is not defined', 'status' => 400]);
        }

        if ($main_data['status'] === 'checkin-checkout-day') {
            return response()->json(['message' => $main_data['message'], 'status' => 400]);
        }

        return response()->json(['message' => 'Invalid Calling', 'status' => 400]);
    }
}
