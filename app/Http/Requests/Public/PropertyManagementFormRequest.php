<?php

namespace App\Http\Requests\Public;

/**
 * PropertyManagementFormRequest — validates property management inquiry.
 *
 * Legacy: Validator::make in PageController::propertyManagementPost
 */
class PropertyManagementFormRequest extends PublicFormRequest
{
    public function rules(): array
    {
        return [
            'email'               => 'required|email',
            'first_name'          => 'required|string|max:191',
            'last_name'           => 'nullable|string|max:191',
            'mobile'              => 'nullable|string|max:50',
            'property_address'    => 'nullable|string',
            'property_type'       => 'nullable|string',
            'number_of_bedrooms'  => 'nullable|string',
            'number_of_bathrooms' => 'nullable|string',
            'what_is_your_rental_goal' => 'nullable|string',
            'what_are_you_looking_to_rent_your_property' => 'nullable|string',
            'is_the_property_currently_closed' => 'nullable|string',
            'message'             => 'nullable|string',
        ];
    }
}
