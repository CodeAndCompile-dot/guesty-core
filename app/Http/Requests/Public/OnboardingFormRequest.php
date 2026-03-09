<?php

namespace App\Http\Requests\Public;

/**
 * OnboardingFormRequest — validates owner onboarding form.
 *
 * Legacy: Validator::make in PageController::onboardingPost
 */
class OnboardingFormRequest extends PublicFormRequest
{
    public function rules(): array
    {
        return [
            'email'                               => 'required|email',
            'first_name'                          => 'nullable|string|max:191',
            'last_name'                           => 'nullable|string|max:191',
            'mobile'                              => 'nullable|string|max:50',
            'bill_to_address'                     => 'nullable|string',
            'rental_property_address'             => 'nullable|string',
            'owner_birthday'                      => 'nullable|string',
            'company_name'                        => 'nullable|string',
            'social_security_number'              => 'nullable|string',
            'business_ein_number'                 => 'nullable|string',
            'routing_number_of_deposites'         => 'nullable|string',
            'account_number'                      => 'nullable|string',
            'account_name'                        => 'nullable|string',
            'account_card_number'                 => 'nullable|string',
            'account_exp'                         => 'nullable|string',
            'account_cvv'                         => 'nullable|string',
            'housekeeping_closet_access'          => 'nullable|string',
            'wifi_lock_Access'                    => 'nullable|string',
            'security_camera_login_instruction'   => 'nullable|string',
            'file1'                               => 'nullable|file|max:10240',
            'file2'                               => 'nullable|file|max:10240',
        ];
    }
}
