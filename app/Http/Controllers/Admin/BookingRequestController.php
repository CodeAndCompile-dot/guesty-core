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
}
