<?php

namespace App\Services\Communication;

use App\Models\EmailTemplete;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * EmailService — central email dispatch for the entire application.
 *
 * Replaces legacy MailHelper (emailSender + emailSenderByController).
 *
 * Two modes:
 *  1. Template-based: DB-stored EmailTemplete with placeholder replacement
 *  2. Blade-rendered: Pre-rendered HTML from a Blade view
 */
class EmailService
{
    /* -------------------------------------------------------------- */
    /*  Template-based emails (legacy: MailHelper::emailSender)       */
    /* -------------------------------------------------------------- */

    /**
     * Send an email using a DB-stored template with placeholder replacement.
     *
     * @param  array  $mailData  Keys: type (email_type), to (comma-sep), plus any placeholder values
     */
    public function sendFromTemplate(array $mailData): void
    {
        try {
            $template = EmailTemplete::where('email_type', $mailData['type'] ?? '')->first();

            if (! $template) {
                Log::warning('Email template not found', ['type' => $mailData['type'] ?? 'unknown']);
                return;
            }

            $body    = $this->replacePlaceholders($template->email_body ?? '', $mailData);
            $subject = $template->email_subject ?? 'Notification';
            $to      = $this->parseRecipients($mailData['to'] ?? '');

            if (empty($to)) {
                Log::warning('No recipients for template email', ['type' => $mailData['type'] ?? '']);
                return;
            }

            $this->dispatch($body, $to, $subject);
        } catch (\Throwable $e) {
            Log::error('Template email failed', ['type' => $mailData['type'] ?? '', 'error' => $e->getMessage()]);
        }
    }

    /* -------------------------------------------------------------- */
    /*  Blade-rendered emails (legacy: MailHelper::emailSenderByController) */
    /* -------------------------------------------------------------- */

    /**
     * Send an email with pre-rendered HTML content.
     *
     * @param  string        $html     The fully-rendered HTML body
     * @param  string|array  $to       Recipient(s)
     * @param  string        $subject  Email subject
     * @param  array         $files    File paths to attach
     */
    public function sendRenderedHtml(string $html, string|array $to, string $subject, array $files = []): void
    {
        try {
            $recipients = is_array($to) ? $to : $this->parseRecipients($to);

            if (empty($recipients)) {
                Log::warning('No recipients for rendered email', ['subject' => $subject]);
                return;
            }

            $this->dispatch($html, $recipients, $subject, $files);
        } catch (\Throwable $e) {
            Log::error('Rendered email failed', ['subject' => $subject, 'error' => $e->getMessage()]);
        }
    }

    /* -------------------------------------------------------------- */
    /*  Convenience: render a Blade view then send                     */
    /* -------------------------------------------------------------- */

    /**
     * Render a Blade view to HTML and send via email.
     */
    public function sendFromView(string $view, array $data, string|array $to, string $subject, array $files = []): void
    {
        $html = view($view, $data)->render();
        $this->sendRenderedHtml($html, $to, $subject, $files);
    }

    /* -------------------------------------------------------------- */
    /*  Internal helpers                                                */
    /* -------------------------------------------------------------- */

    /**
     * Dispatch the email via Laravel's Mail facade.
     */
    protected function dispatch(string $body, array $recipients, string $subject, array $files = []): void
    {
        $fromEmail = \ModelHelper::getDataFromSetting('mail_from') ?? config('mail.from.address', 'noreply@example.com');
        $fromName  = \ModelHelper::getDataFromSetting('mail_from_name') ?? config('mail.from.name', 'System');

        Mail::send('mail.dummyMail', ['email_body' => $body], function ($message) use ($recipients, $subject, $fromEmail, $fromName, $files) {
            $message->to($recipients)
                ->subject($subject)
                ->from($fromEmail, $fromName);

            foreach ($files as $file) {
                if (is_string($file) && file_exists($file)) {
                    $message->attach($file);
                }
            }
        });
    }

    /**
     * Replace template placeholders with actual values.
     *
     * Legacy: MailHelper::emailSender did ~30 regex replacements.
     * We keep the same {placeholder} format for backward compatibility.
     */
    protected function replacePlaceholders(string $body, array $data): string
    {
        $map = [
            '{username}'        => $data['username'] ?? $data['name'] ?? '',
            '{useremail}'       => $data['useremail'] ?? $data['email'] ?? '',
            '{usermobile}'      => $data['usermobile'] ?? $data['mobile'] ?? '',
            '{first_name}'      => $data['first_name'] ?? '',
            '{last_name}'       => $data['last_name'] ?? '',
            '{email}'           => $data['email'] ?? '',
            '{mobile}'          => $data['mobile'] ?? '',
            '{usermessage}'     => $data['usermessage'] ?? $data['message'] ?? '',
            '{date_of_request}' => $data['date_of_request'] ?? '',
            '{guests}'          => $data['guests'] ?? '',
            '{budget}'          => $data['budget'] ?? '',
            // Onboarding
            '{bill_to_address}'          => $data['bill_to_address'] ?? '',
            '{rental_property_address}'  => $data['rental_property_address'] ?? '',
            '{owner_birthday}'           => $data['owner_birthday'] ?? '',
            '{company_name}'             => $data['company_name'] ?? '',
            '{social_security_number}'   => $data['social_security_number'] ?? '',
            '{business_ein_number}'      => $data['business_ein_number'] ?? '',
            '{routing_number_of_deposites}' => $data['routing_number_of_deposites'] ?? '',
            '{account_number}'           => $data['account_number'] ?? '',
            '{account_name}'             => $data['account_name'] ?? '',
            '{account_card_number}'      => $data['account_card_number'] ?? '',
            '{account_exp}'              => $data['account_exp'] ?? '',
            '{account_cvv}'              => $data['account_cvv'] ?? '',
            '{housekeeping_closet_access}' => $data['housekeeping_closet_access'] ?? '',
            '{wifi_lock_Access}'         => $data['wifi_lock_Access'] ?? '',
            '{security_camera_login_instruction}' => $data['security_camera_login_instruction'] ?? '',
            // Property management
            '{property_address}'   => $data['property_address'] ?? '',
            '{property_type}'      => $data['property_type'] ?? '',
            '{number_of_bedrooms}'  => $data['number_of_bedrooms'] ?? '',
            '{number_of_bathrooms}' => $data['number_of_bathrooms'] ?? '',
            '{what_is_your_rental_goal}' => $data['what_is_your_rental_goal'] ?? '',
            '{what_are_you_looking_to_rent_your_property}' => $data['what_are_you_looking_to_rent_your_property'] ?? '',
            '{is_the_property_currently_closed}' => $data['is_the_property_currently_closed'] ?? '',
            '{revenue_analysis}' => $data['revenue_analysis'] ?? '',
        ];

        return str_replace(array_keys($map), array_values($map), $body);
    }

    /**
     * Parse comma-separated recipients into an array.
     */
    protected function parseRecipients(string $to): array
    {
        if (empty($to)) {
            return [];
        }

        return array_filter(
            array_map('trim', explode(',', $to)),
            fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL),
        );
    }
}
