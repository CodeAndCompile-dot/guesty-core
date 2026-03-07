<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates admin booking-enquiry create/update requests.
 *
 * Legacy had empty validation rules (Validator::make($request->all(), [])).
 * We add sensible rules while keeping the same field set for compatibility.
 */
class BookingRequestFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'booking_type_admin' => 'required|in:invoice,manual',
            'property_id'       => 'required|integer|exists:properties,id',
            'checkin'            => 'required|date',
            'checkout'           => 'required|date|after:checkin',
            'adults'             => 'nullable|integer|min:0|max:100',
            'child'              => 'nullable|integer|min:0|max:100',
            'name'               => 'required|string|max:255',
            'email'              => 'required|email|max:255',
            'mobile'             => 'nullable|string|max:50',
            'message'            => 'nullable|string',
            'extra_discount'     => 'nullable|numeric|min:0',
            'pets'               => 'nullable|integer|min:0|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'property_id.required' => 'Please select a property.',
            'checkin.required'     => 'Check-in date is required.',
            'checkout.after'       => 'Check-out must be after check-in.',
            'name.required'        => 'Guest name is required.',
            'email.required'       => 'Guest email is required.',
        ];
    }
}
