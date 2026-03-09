<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\NewsletterRequest;
use App\Models\NewsLetter;
use App\Services\Communication\EmailService;

/**
 * NewsletterController — handles newsletter subscription (AJAX).
 *
 * Legacy: PageController::newsletterPost()
 */
class NewsletterController extends Controller
{
    public function __construct(
        protected EmailService $emailService,
    ) {}

    /**
     * Subscribe to the newsletter.
     *
     * Returns JSON (used via AJAX on the frontend).
     */
    public function store(NewsletterRequest $request)
    {
        // Double-check uniqueness (FormRequest already validates unique:newsletters,email)
        $existing = NewsLetter::where('email', $request->email)->first();

        if ($existing) {
            return response()->json(['status' => 400, 'message' => 'Already subscribed']);
        }

        NewsLetter::create(['email' => $request->email]);

        $this->emailService->sendFromTemplate([
            'type'      => 'newsletter',
            'useremail' => $request->email,
            'to'        => \ModelHelper::getDataFromSetting('contact_us_receiving_mail') ?? '',
        ]);

        return response()->json(['status' => 200, 'message' => "You're on the list! Thanks for subscribing."]);
    }
}
