<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\OnboardingFormRequest;
use App\Models\OnboardingRequest;
use App\Services\Communication\EmailService;
use App\Services\Media\UploadService;

/**
 * OnboardingController — handles property-owner onboarding form submissions.
 *
 * Legacy: PageController::onboardingPost()
 */
class OnboardingController extends Controller
{
    public function __construct(
        protected EmailService $emailService,
        protected UploadService $uploadService,
    ) {}

    /**
     * Store a new onboarding request.
     */
    public function store(OnboardingFormRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('file1')) {
            $data['file1'] = $this->uploadService->upload($request->file('file1'), 'cms');
        }

        if ($request->hasFile('file2')) {
            $data['file2'] = $this->uploadService->upload($request->file('file2'), 'cms');
        }

        OnboardingRequest::create($data);

        $this->emailService->sendFromTemplate([
            'type'                             => 'Onboarding_admin',
            'first_name'                       => $request->first_name ?? '',
            'last_name'                        => $request->last_name ?? '',
            'email'                            => $request->email,
            'mobile'                           => $request->mobile ?? '',
            'bill_to_address'                  => $request->bill_to_address ?? '',
            'rental_property_address'          => $request->rental_property_address ?? '',
            'owner_birthday'                   => $request->owner_birthday ?? '',
            'company_name'                     => $request->company_name ?? '',
            'social_security_number'           => $request->social_security_number ?? '',
            'business_ein_number'              => $request->business_ein_number ?? '',
            'routing_number_of_deposites'      => $request->routing_number_of_deposites ?? '',
            'account_number'                   => $request->account_number ?? '',
            'account_name'                     => $request->account_name ?? '',
            'account_card_number'              => $request->account_card_number ?? '',
            'account_exp'                      => $request->account_exp ?? '',
            'account_cvv'                      => $request->account_cvv ?? '',
            'housekeeping_closet_access'       => $request->housekeeping_closet_access ?? '',
            'wifi_lock_Access'                 => $request->wifi_lock_Access ?? '',
            'security_camera_login_instruction' => $request->security_camera_login_instruction ?? '',
            'to'                               => \ModelHelper::getDataFromSetting('contact_us_receiving_mail') ?? '',
        ]);

        return redirect()->back()->with('success', 'Thank you for submitting your query, we will get in touch shortly');
    }
}
