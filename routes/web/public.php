<?php

/*
|--------------------------------------------------------------------------
| Public Web Routes
|--------------------------------------------------------------------------
|
| Routes for the public-facing website: homepage, properties, blogs,
| attractions, contact, newsletter, booking flow, etc.
|
*/

use App\Http\Controllers\ICalController;
use App\Http\Controllers\Payment\PaypalController;
use App\Http\Controllers\Payment\ReceiptController;
use App\Http\Controllers\Payment\StripeController;
use App\Http\Controllers\Public\BookingController;
use App\Http\Controllers\Public\CaptchaController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\NewsletterController;
use App\Http\Controllers\Public\OnboardingController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\ReviewController;
use App\Http\Controllers\Public\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Phase 6: iCal / PriceLabs / Cron Endpoints (public, no auth)
|--------------------------------------------------------------------------
| Legacy: These are unprotected GET routes used by cron jobs and external
| calendar subscribers. We preserve them as-is for backward compatibility.
*/
Route::get('set-cron-job', [ICalController::class, 'setCronJob']);
Route::get('refresh-calendar-data', [ICalController::class, 'refresshCalendar'])->name('refresshCalendar');
Route::get('set-pricelab', [ICalController::class, 'setPriceLab'])->name('setPriceLab');
Route::get('send-welcome-packages', [ICalController::class, 'sendWelcomePackage'])->name('sendWelcomePackage');
Route::get('send-review-email', [ICalController::class, 'sendReviewEmail'])->name('sendReviewEmail');

/*
|--------------------------------------------------------------------------
| Phase 8: Payment Routes (public, no auth)
|--------------------------------------------------------------------------
| Legacy: Payment pages are accessed by guests via emailed links.
| No authentication required — the booking ID acts as the access token.
*/
Route::get('booking/payment/paypal/{id}', [PaypalController::class, 'index'])->name('paypal');
Route::get('booking/payment/paypal/post/{id}', [PaypalController::class, 'verify'])->name('paypal.submit');

Route::get('booking/payment/{id}', [StripeController::class, 'index'])->name('stripe_payment');
Route::post('booking/payment/{id}', [StripeController::class, 'store'])->name('stripe.post');

Route::get('getIntendentData', [StripeController::class, 'getIntentData']);
Route::post('payment_init', [StripeController::class, 'paymentInit'])->name('payment_init');

Route::get('payment/success/{id}', [ReceiptController::class, 'show'])->name('payment.success');

/*
|--------------------------------------------------------------------------
| Phase 10: Form POST Endpoints (public, no auth)
|--------------------------------------------------------------------------
*/
Route::post('contact-post', [ContactController::class, 'store'])->name('contactPost');
Route::post('property-management-post', [ContactController::class, 'propertyManagement'])->name('property-management-post');
Route::post('onboarding-post', [OnboardingController::class, 'store'])->name('onboardingPost');
Route::post('newsletter-post', [NewsletterController::class, 'store'])->name('newsletterPost');
Route::post('review-submit', [ReviewController::class, 'store'])->name('reviewSubmit');

/*
|--------------------------------------------------------------------------
| Phase 10: Booking Flow Routes (public, no auth)
|--------------------------------------------------------------------------
*/
Route::post('save-booking-data', [BookingController::class, 'saveBookingData'])->name('save-booking-data');
Route::post('rental-aggrement-data-save', [BookingController::class, 'rentalAggrementDataSave'])->name('rental-aggrement-data-save');
Route::post('checkajax-get-quote', [BookingController::class, 'checkAjaxGetQuoteData'])->name('checkajax-get-quote');
Route::get('get-quote-after/{id}', [BookingController::class, 'getQuoteAfter']);
Route::post('update-payment-booking-data/{id}', [BookingController::class, 'updatepaymentBookingData'])->name('update-payment-booking-data');

/*
|--------------------------------------------------------------------------
| Phase 10: Utility Routes
|--------------------------------------------------------------------------
*/
Route::get('reload-captcha', [CaptchaController::class, 'reload']);
Route::get('sitemap.xml', [SitemapController::class, 'index']);

Route::get('robots.txt', function () {
    return response(view('front.robots'), 200, ['Content-Type' => 'text/plain']);
});

/*
|--------------------------------------------------------------------------
| Phase 10: Public Page Routes (must be last — catch-all slug)
|--------------------------------------------------------------------------
*/
Route::get('meet-the-team/{seo_url}', [PageController::class, 'teamMember'])->name('meet-the-team');
Route::get('vacation/{seo_url}', [PageController::class, 'vacation']);
Route::get('blog/{seo_url}', [PageController::class, 'blogSingle']);
Route::get('blogs/category/{seo_url}', [PageController::class, 'blogCategory']);
Route::get('properties/location/{seo_url}', [PageController::class, 'propertyLocation']);
Route::get('attractions/detail/{seo_url}', [PageController::class, 'attractionSingle']);
Route::get('attractions/location/{seo_url}', [PageController::class, 'attractionLocation']);
Route::get('attractions/category/{seo_url}', [PageController::class, 'attractionCategory']);

Route::get('booking/preview/{id}', [BookingController::class, 'previewBooking']);
Route::get('booking/rental-aggrement/{id}', [BookingController::class, 'rentalAggrementBooking'])->name('booking.rental_aggrement');

Route::get('/', [HomeController::class, 'index'])->name('front-home');
Route::get('{seo_url}', [PageController::class, 'cmsPage'])->name('services');
