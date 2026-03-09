<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

/**
 * CaptchaController — reload captcha image (AJAX).
 *
 * Legacy: PageController::reloadCaptcha
 */
class CaptchaController extends Controller
{
    public function reload()
    {
        if (function_exists('captcha_img')) {
            return response()->json(['captcha' => captcha_img()]);
        }

        return response()->json(['captcha' => '']);
    }
}
