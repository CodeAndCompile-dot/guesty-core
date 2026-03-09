<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\ContactFormRequest;
use App\Http\Requests\Public\PropertyManagementFormRequest;
use App\Models\ContactusRequest;
use App\Models\PropertyManagementRequest;
use App\Services\Communication\EmailService;

/**
 * ContactController — handles contact and property-management form submissions.
 *
 * Legacy: PageController::contactPost(), PageController::propertyManagementPost()
 */
class ContactController extends Controller
{
    public function __construct(
        protected EmailService $emailService,
    ) {}

    /**
     * Handle contact-us form submission.
     *
     * Legacy: PageController::contactPost()
     */
    public function store(ContactFormRequest $request)
    {
        // Silently swallow blocked emails (legacy behavior — returns success anyway)
        $blockedEmails = array_map('trim', explode(',', \ModelHelper::getDataFromSetting('blocked_email') ?? ''));

        if (in_array($request->email, $blockedEmails, true)) {
            return redirect()->back()->with('success', 'Thank you for submitting your query, we will get in touch shortly');
        }

        ContactusRequest::create($request->validated());

        // Email to user
        $this->emailService->sendFromTemplate([
            'type'     => 'thank_you_for_feedback_user',
            'username' => $request->name,
            'to'       => $request->email,
        ]);

        // Email to admin
        $this->emailService->sendFromTemplate([
            'type'        => 'feedback_admin',
            'username'    => $request->name,
            'useremail'   => $request->email,
            'usermobile'  => $request->mobile,
            'usermessage' => $request->message,
            'to'          => \ModelHelper::getDataFromSetting('contact_us_receiving_mail') ?? '',
        ]);

        return redirect()->back()->with('success', 'Thank you for submitting your query, we will get in touch shortly');
    }

    /**
     * Handle property-management inquiry form.
     *
     * Legacy: PageController::propertyManagementPost()
     */
    public function propertyManagement(PropertyManagementFormRequest $request)
    {
        $data         = $request->validated();
        $data['name'] = ($request->first_name ?? '') . ' ' . ($request->last_name ?? '');

        PropertyManagementRequest::create($data);

        // Email to user
        $this->emailService->sendFromTemplate([
            'type'     => 'thank_you_for_property_management_user',
            'username' => $data['name'],
            'to'       => $request->email,
        ]);

        // Email to admin
        $this->emailService->sendFromTemplate([
            'type'             => 'property_management_admin',
            'username'         => $data['name'],
            'useremail'        => $request->email,
            'usermobile'       => $request->mobile,
            'property_address' => $request->property_address ?? '',
            'property_type'    => $request->property_type ?? '',
            'usermessage'      => $request->message ?? '',
            'revenue_analysis' => $request->what_is_your_rental_goal ?? '',
            'to'               => \ModelHelper::getDataFromSetting('contact_us_receiving_mail') ?? '',
        ]);

        return redirect()->back()->with('success', 'Thank you for submitting your query, we will get in touch shortly');
    }
}
