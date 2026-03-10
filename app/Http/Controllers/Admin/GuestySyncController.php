<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

/**
 * GuestySyncController — handles Guesty API sync operations
 * triggered from the admin panel buttons.
 *
 * Legacy: These were PageController methods (getPropertyData, getBookingData,
 * getToken, getBookingToken, getReviewData) exposed as public routes.
 * They are now behind auth as admin-only operations.
 */
class GuestySyncController extends Controller
{
    /**
     * Sync all properties from Guesty API.
     * Legacy: PageController::getPropertyData()
     */
    public function syncProperties()
    {
        try {
            $result = \GuestyApi::getPropertyData();

            $message = ($result['status'] ?? 0) === 200
                ? 'Properties synced successfully (' . ($result['count'] ?? 0) . ' properties)'
                : 'Property sync failed: ' . ($result['error'] ?? 'Unknown error');

            return redirect()->back()->with(
                ($result['status'] ?? 0) === 200 ? 'success' : 'danger',
                $message,
            );
        } catch (\Throwable $e) {
            Log::error('Guesty property sync failed', ['error' => $e->getMessage()]);

            return redirect()->back()->with('danger', 'Property sync failed: ' . $e->getMessage());
        }
    }

    /**
     * Sync all bookings from Guesty API.
     * Legacy: PageController::getBookingData()
     */
    public function syncBookings()
    {
        try {
            $result = \GuestyApi::getBookingData();

            $message = ($result['status'] ?? 0) === 200
                ? 'Bookings synced successfully (' . ($result['count'] ?? 0) . ' bookings)'
                : 'Booking sync failed: ' . ($result['error'] ?? 'Unknown error');

            return redirect()->back()->with(
                ($result['status'] ?? 0) === 200 ? 'success' : 'danger',
                $message,
            );
        } catch (\Throwable $e) {
            Log::error('Guesty booking sync failed', ['error' => $e->getMessage()]);

            return redirect()->back()->with('danger', 'Booking sync failed: ' . $e->getMessage());
        }
    }

    /**
     * Sync all reviews from Guesty API.
     * Legacy: PageController::getReviewData()
     */
    public function syncReviews()
    {
        try {
            $result = \GuestyApi::getReviewData();

            $message = ($result['status'] ?? 0) === 200
                ? 'Reviews synced successfully (' . ($result['count'] ?? 0) . ' reviews)'
                : 'Review sync failed: ' . ($result['error'] ?? 'Unknown error');

            return redirect()->back()->with(
                ($result['status'] ?? 0) === 200 ? 'success' : 'danger',
                $message,
            );
        } catch (\Throwable $e) {
            Log::error('Guesty review sync failed', ['error' => $e->getMessage()]);

            return redirect()->back()->with('danger', 'Review sync failed: ' . $e->getMessage());
        }
    }

    /**
     * Refresh the Guesty Open API token.
     * Legacy: PageController::getToken()
     */
    public function refreshToken()
    {
        try {
            $token = \GuestyApi::getToken();

            return redirect()->back()->with(
                $token ? 'success' : 'danger',
                $token ? 'Open API token refreshed successfully' : 'Token refresh failed',
            );
        } catch (\Throwable $e) {
            Log::error('Guesty token refresh failed', ['error' => $e->getMessage()]);

            return redirect()->back()->with('danger', 'Token refresh failed: ' . $e->getMessage());
        }
    }

    /**
     * Refresh the Guesty Booking Engine token.
     * Legacy: PageController::getBookingToken()
     */
    public function refreshBookingToken()
    {
        try {
            $token = \GuestyApi::getBookingToken();

            return redirect()->back()->with(
                $token ? 'success' : 'danger',
                $token ? 'Booking token refreshed successfully' : 'Booking token refresh failed',
            );
        } catch (\Throwable $e) {
            Log::error('Guesty booking token refresh failed', ['error' => $e->getMessage()]);

            return redirect()->back()->with('danger', 'Booking token refresh failed: ' . $e->getMessage());
        }
    }
}
