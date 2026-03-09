<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Base form request for public forms.
 * Adds optional Google reCAPTCHA verification (legacy: inline captcha checks).
 */
abstract class PublicFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Perform reCAPTCHA validation after standard rules pass.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            if (! $this->shouldVerifyCaptcha()) {
                return;
            }

            $response = $this->input('g-recaptcha-response');

            if (! $response) {
                $validator->errors()->add('captcha', 'Please check on the reCAPTCHA box.');
                return;
            }

            $secretKey = \ModelHelper::getDataFromSetting('google_captcha_secret_key');

            if (empty($secretKey)) {
                return; // No secret configured, skip
            }

            try {
                $verify = file_get_contents(
                    'https://www.google.com/recaptcha/api/siteverify?secret=' . $secretKey . '&response=' . $response
                );
                $result = json_decode($verify);

                if (! ($result->success ?? false)) {
                    $validator->errors()->add('captcha', 'Robot verification failed, please try again.');
                }
            } catch (\Throwable $e) {
                // Silently pass if reCAPTCHA service is unreachable
            }
        });
    }

    /**
     * Determine if captcha validation should run.
     */
    protected function shouldVerifyCaptcha(): bool
    {
        $enabled = \ModelHelper::getDataFromSetting('g_captcha_enabled');

        if (! $enabled || $enabled !== 'yes') {
            return false;
        }

        $siteKey   = \ModelHelper::getDataFromSetting('google_captcha_site_key');
        $secretKey = \ModelHelper::getDataFromSetting('google_captcha_secret_key');

        return ! empty($siteKey) && ! empty($secretKey);
    }
}
