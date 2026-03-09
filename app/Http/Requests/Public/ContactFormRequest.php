<?php

namespace App\Http\Requests\Public;

/**
 * ContactFormRequest — validates contact-us form submissions.
 *
 * Legacy: Validator::make in PageController::contactPost
 */
class ContactFormRequest extends PublicFormRequest
{
    public function rules(): array
    {
        return [
            'email'   => 'required|email',
            'name'    => 'required|string|max:191',
            'message' => 'required|string',
            'mobile'  => 'nullable|string|max:50',
            'date_of_request' => 'nullable|string',
            'budget'  => 'nullable|string',
            'guests'  => 'nullable|string',
        ];
    }
}
