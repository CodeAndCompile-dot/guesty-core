<?php

/*
|--------------------------------------------------------------------------
| Admin Web Routes
|--------------------------------------------------------------------------
|
| Routes for the admin panel. All routes here are prefixed with /client-login
| and protected by the auth middleware, matching the legacy routing structure.
|
*/

use App\Http\Controllers\Admin\AttractionCategoryController;
use App\Http\Controllers\Admin\AttractionController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BookingRequestController;
use App\Http\Controllers\Admin\CkeditorController;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\ContactusRequestController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmailTempleteController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\GuestyPropertyController;
use App\Http\Controllers\Admin\GuestySyncController;
use App\Http\Controllers\Admin\LandingCmsController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\MaximizeAssetController;
use App\Http\Controllers\Admin\NewsLetterController;
use App\Http\Controllers\Admin\OnboardingRequestController;
use App\Http\Controllers\Admin\OurClientController;
use App\Http\Controllers\Admin\OurTeamController;
use App\Http\Controllers\Admin\PropertyAmenityController;
use App\Http\Controllers\Admin\PropertyAmenityGroupController;
use App\Http\Controllers\Admin\PropertyCalendarController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\PropertyManagementRequestController;
use App\Http\Controllers\Admin\PropertyRateController;
use App\Http\Controllers\Admin\PropertyRoomController;
use App\Http\Controllers\Admin\PropertyRoomItemController;
use App\Http\Controllers\Admin\SeoCmsController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WelcomePackageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('client-login')->group(function () {

    /*
    |----------------------------------------------------------------------
    | Dashboard & Utility Routes
    |----------------------------------------------------------------------
    */
    Route::get('/', [DashboardController::class, 'index']);

    // Settings
    Route::get('setting', [DashboardController::class, 'setting'])->name('setting');
    Route::post('setting', [DashboardController::class, 'settingPost'])->name('setting.post');

    // Media Center
    Route::get('media-center', [DashboardController::class, 'mediaCenter'])->name('media-center');
    Route::post('new-file-uploads', [DashboardController::class, 'newFileUploads'])->name('new-file-uploads');
    Route::match(['post', 'delete'], 'medias-destroy', [DashboardController::class, 'mediasDelete'])->name('medias-destroy');

    // Bulk Delete (legacy URI alias + current)
    Route::post('multipleDelete/{model}', [DashboardController::class, 'multipleDelete'])->name('multipleDelete');
    Route::post('multiple-delete/{model}', [DashboardController::class, 'multipleDelete']);

    // Password Change
    Route::get('change-password', [DashboardController::class, 'changePassword'])->name('change-password');
    Route::post('change-password', [DashboardController::class, 'changePasswordPost'])->name('change-password.post');

    /*
    |----------------------------------------------------------------------
    | CKEditor
    |----------------------------------------------------------------------
    */
    Route::get('ckeditor', [CkeditorController::class, 'index'])->name('ckeditor');
    Route::post('ckeditor/upload', [CkeditorController::class, 'upload'])->name('ckeditor.upload');

    /*
    |----------------------------------------------------------------------
    | Resource Routes — matching legacy Route::resources() names exactly
    |----------------------------------------------------------------------
    */
    Route::resources([
        'users' => UserController::class,
        'sliders' => SliderController::class,
        'cms' => CmsController::class,
        'welcome_packages' => WelcomePackageController::class,
        'email-templetes' => EmailTempleteController::class,
        'faqs' => FaqController::class,
        'galleries' => GalleryController::class,
        'newsletters' => NewsLetterController::class,
        'our-clients' => OurClientController::class,
        'testimonials' => TestimonialController::class,
        'our-teams' => OurTeamController::class,
        'services' => ServiceController::class,
        'contact-us-enquiries' => ContactusRequestController::class,
        'landing_cms' => LandingCmsController::class,
        'seo_pages' => SeoCmsController::class,
        'onboarding_requests' => OnboardingRequestController::class,
        'maximize-assets' => MaximizeAssetController::class,
        'property_management_requests' => PropertyManagementRequestController::class,
        'properties' => PropertyController::class,
        'locations' => LocationController::class,
        'coupons' => CouponController::class,
    ]);

    /*
    |----------------------------------------------------------------------
    | Extra Routes — copyData, active, deactive (legacy explicit routes)
    |----------------------------------------------------------------------
    */

    // Sliders
    Route::get('sliders/copydata/{id}', [SliderController::class, 'copyData'])->name('sliders.copyData');
    Route::get('sliders/active/{id}', [SliderController::class, 'active'])->name('sliders.active');
    Route::get('sliders/deactive/{id}', [SliderController::class, 'deactive'])->name('sliders.deactive');

    // FAQs
    Route::get('faqs/copydata/{id}', [FaqController::class, 'copyData'])->name('faqs.copyData');

    // Galleries
    Route::get('galleries/copydata/{id}', [GalleryController::class, 'copyData'])->name('galleries.copyData');
    Route::get('galleries/active/{id}', [GalleryController::class, 'active'])->name('galleries.active');
    Route::get('galleries/deactive/{id}', [GalleryController::class, 'deactive'])->name('galleries.deactive');

    // Testimonials
    Route::get('testimonials/copydata/{id}', [TestimonialController::class, 'copyData'])->name('testimonials.copyData');

    /*
    |----------------------------------------------------------------------
    | Phase 3: Property Extra Routes
    |----------------------------------------------------------------------
    */
    Route::get('properties/copydata/{id}', [PropertyController::class, 'copyData'])->name('properties.copyData');
    Route::get('properties/active/{id}', [PropertyController::class, 'active'])->name('properties.active');
    Route::get('properties/deactive/{id}', [PropertyController::class, 'deactive'])->name('properties.deactive');
    Route::post('properties/update-property-caption-and-sorting', [PropertyController::class, 'updateCaptionSort'])->name('update-property-caption-and-sorting');
    Route::post('image-delete-asset', [PropertyController::class, 'imageDeleteAsset'])->name('image-delete-asset');
    Route::get('delete-property-space-single', [PropertyController::class, 'deletePropertySpace'])->name('delete-property-space-single');

    /*
    |----------------------------------------------------------------------
    | Phase 6: Property Calendar (iCal import/export per property)
    |----------------------------------------------------------------------
    */
    Route::get('properties/{property_id}/calendar', [PropertyCalendarController::class, 'index'])->name('properties-calendar.index');
    Route::get('properties/{property_id}/calendar/import-list', [PropertyCalendarController::class, 'importlist'])->name('properties-calendar.import-list');
    Route::get('properties/{property_id}/calendar/import-list-refresh/{id}', [PropertyCalendarController::class, 'importlistRefresh'])->name('properties-calendar.importlistRefresh');
    Route::get('properties/{property_id}/calendar/create', [PropertyCalendarController::class, 'create'])->name('properties-calendar.create');
    Route::post('properties/{property_id}/calendar/create', [PropertyCalendarController::class, 'store'])->name('properties-calendar.store');
    Route::delete('properties/{property_id}/calendar/{id}/delete', [PropertyCalendarController::class, 'destroy'])->name('properties-calendar.destroy');
    Route::get('properties/{property_id}/calendar/self-ical-refresh', [PropertyCalendarController::class, 'selfIcalRefresh'])->name('properties-calendar.selfIcalRefresh');

    /*
    |----------------------------------------------------------------------
    | Phase 3: Property Rates (nested under property)
    |----------------------------------------------------------------------
    */
    Route::get('properties/{property_id}/rates', [PropertyRateController::class, 'index'])->name('properties-rates');
    Route::get('properties/{property_id}/rates/create', [PropertyRateController::class, 'create'])->name('properties-rates.create');
    Route::post('properties/{property_id}/rates/create', [PropertyRateController::class, 'store'])->name('properties-rates.store');
    Route::get('properties/{property_id}/rates/{id}/edit', [PropertyRateController::class, 'edit'])->name('properties-rates.edit');
    Route::put('properties/{property_id}/rates/{id}/update', [PropertyRateController::class, 'update'])->name('properties-rates.update');
    Route::delete('properties/{property_id}/rates/{id}/delete', [PropertyRateController::class, 'destroy'])->name('properties-rates.destroy');

    /*
    |----------------------------------------------------------------------
    | Phase 3: Property Amenity Groups (nested under property)
    |----------------------------------------------------------------------
    */
    Route::get('properties/{property_id}/group-amenities', [PropertyAmenityGroupController::class, 'index'])->name('properties-group-amenities');
    Route::get('properties/{property_id}/group-amenities/create', [PropertyAmenityGroupController::class, 'create'])->name('properties-group-amenities.create');
    Route::post('properties/{property_id}/group-amenities/create', [PropertyAmenityGroupController::class, 'store'])->name('properties-group-amenities.store');
    Route::get('properties/{property_id}/group-amenities/{id}/edit', [PropertyAmenityGroupController::class, 'edit'])->name('properties-group-amenities.edit');
    Route::put('properties/{property_id}/group-amenities/{id}/update', [PropertyAmenityGroupController::class, 'update'])->name('properties-group-amenities.update');
    Route::get('properties/{property_id}/group-amenities/{id}/delete', [PropertyAmenityGroupController::class, 'destroy'])->name('properties-group-amenities.destroy');
    Route::get('properties/{property_id}/group-amenities/{id}/active', [PropertyAmenityGroupController::class, 'active'])->name('properties-group-amenities.active');
    Route::get('properties/{property_id}/group-amenities/{id}/deactive', [PropertyAmenityGroupController::class, 'deactive'])->name('properties-group-amenities.deactive');

    /*
    |----------------------------------------------------------------------
    | Phase 3: Property Amenities (nested under property → group)
    |----------------------------------------------------------------------
    */
    Route::get('properties/{property_id}/group-amenities/{group_id}/amenities', [PropertyAmenityController::class, 'index'])->name('properties-amenities');
    Route::get('properties/{property_id}/group-amenities/{group_id}/amenities/create', [PropertyAmenityController::class, 'create'])->name('properties-amenities.create');
    Route::post('properties/{property_id}/group-amenities/{group_id}/amenities/store', [PropertyAmenityController::class, 'store'])->name('properties-amenities.store');
    Route::get('properties/{property_id}/group-amenities/{group_id}/amenities/{id}/edit', [PropertyAmenityController::class, 'edit'])->name('properties-amenities.edit');
    Route::put('properties/{property_id}/group-amenities/{group_id}/amenities/{id}/update', [PropertyAmenityController::class, 'update'])->name('properties-amenities.update');
    Route::delete('properties/{property_id}/group-amenities/{group_id}/amenities/{id}/delete', [PropertyAmenityController::class, 'destroy'])->name('properties-amenities.destroy');
    Route::get('properties/{property_id}/group-amenities/{group_id}/amenities/{id}/active', [PropertyAmenityController::class, 'active'])->name('properties-amenities.active');
    Route::get('properties/{property_id}/group-amenities/{group_id}/amenities/{id}/deactive', [PropertyAmenityController::class, 'deactive'])->name('properties-amenities.deactive');
    Route::get('properties/{property_id}/group-amenities/{group_id}/amenities/copydata/{id}', [PropertyAmenityController::class, 'copyData'])->name('properties-amenities.copyData');

    /*
    |----------------------------------------------------------------------
    | Phase 3: Property Rooms (nested under property)
    |----------------------------------------------------------------------
    */
    Route::get('properties/{property_id}/rooms', [PropertyRoomController::class, 'index'])->name('properties-group-rooms');
    Route::get('properties/{property_id}/rooms/create', [PropertyRoomController::class, 'create'])->name('properties-group-rooms.create');
    Route::post('properties/{property_id}/rooms/create', [PropertyRoomController::class, 'store'])->name('properties-group-rooms.store');
    Route::get('properties/{property_id}/rooms/{id}/edit', [PropertyRoomController::class, 'edit'])->name('properties-group-rooms.edit');
    Route::put('properties/{property_id}/rooms/{id}/update', [PropertyRoomController::class, 'update'])->name('properties-group-rooms.update');
    Route::delete('properties/{property_id}/rooms/{id}/delete', [PropertyRoomController::class, 'destroy'])->name('properties-group-rooms.destroy');
    Route::get('properties/{property_id}/rooms/{id}/active', [PropertyRoomController::class, 'active'])->name('properties-group-rooms.active');
    Route::get('properties/{property_id}/rooms/{id}/deactive', [PropertyRoomController::class, 'deactive'])->name('properties-group-rooms.deactive');

    /*
    |----------------------------------------------------------------------
    | Phase 3: Property Room Items / Sub-Rooms (nested under property → room)
    |----------------------------------------------------------------------
    */
    Route::get('properties/{property_id}/rooms/{group_id}/sub-room', [PropertyRoomItemController::class, 'index'])->name('properties-sub-room');
    Route::get('properties/{property_id}/rooms/{group_id}/sub-room/create', [PropertyRoomItemController::class, 'create'])->name('properties-sub-room.create');
    Route::post('properties/{property_id}/rooms/{group_id}/sub-room/store', [PropertyRoomItemController::class, 'store'])->name('properties-sub-room.store');
    Route::get('properties/{property_id}/rooms/{group_id}/sub-room/{id}/edit', [PropertyRoomItemController::class, 'edit'])->name('properties-sub-room.edit');
    Route::put('properties/{property_id}/rooms/{group_id}/sub-room/{id}/update', [PropertyRoomItemController::class, 'update'])->name('properties-sub-room.update');
    Route::delete('properties/{property_id}/rooms/{group_id}/sub-room/{id}/delete', [PropertyRoomItemController::class, 'destroy'])->name('properties-sub-room.destroy');
    Route::get('properties/{property_id}/rooms/{group_id}/sub-room/{id}/active', [PropertyRoomItemController::class, 'active'])->name('properties-sub-room.active');
    Route::get('properties/{property_id}/rooms/{group_id}/sub-room/{id}/deactive', [PropertyRoomItemController::class, 'deactive'])->name('properties-sub-room.deactive');

    /*
    |----------------------------------------------------------------------
    | Phase 4: Attractions & Attraction Categories
    |----------------------------------------------------------------------
    */
    Route::resources([
        'attractions'            => AttractionController::class,
        'attraction-categories'  => AttractionCategoryController::class,
    ]);

    /*
    |----------------------------------------------------------------------
    | Phase 4: Blog & Blog Category
    |----------------------------------------------------------------------
    */
    Route::resources([
        'blogs'         => BlogController::class,
        'blog-category' => BlogCategoryController::class,
    ]);

    // Blog extra routes
    Route::get('blogs/copydata/{id}', [BlogController::class, 'copyData'])->name('blogs.copyData');
    Route::get('blogs/active/{id}', [BlogController::class, 'active'])->name('blogs.active');
    Route::get('blogs/deactive/{id}', [BlogController::class, 'deactive'])->name('blogs.deactive');

    // Blog Category extra routes
    Route::get('blog-category/copydata/{id}', [BlogCategoryController::class, 'copyData'])->name('blog-category.copyData');

    /*
    |----------------------------------------------------------------------
    | Phase 5: Guesty Properties
    |----------------------------------------------------------------------
    */
    Route::resources([
        'guesty_properties' => GuestyPropertyController::class,
    ]);

    // AJAX: Sub-location list for Guesty property edit
    Route::post('getSubLocationList', [GuestyPropertyController::class, 'getSubLocationList'])->name('getSubLocationList');

    // Guesty sync/token routes (referenced by add-bar partial buttons)
    Route::get('set-getPropertyData', [GuestySyncController::class, 'syncProperties'])
        ->name('set-getPropertyData');
    Route::get('set-getBookingData', [GuestySyncController::class, 'syncBookings'])
        ->name('set-getBookingData');
    Route::get('get-reviews-data', [GuestySyncController::class, 'syncReviews'])
        ->name('get-reviews-data');
    Route::get('set-getToken', [GuestySyncController::class, 'refreshToken'])
        ->name('set-getToken');
    Route::get('getBookingToken', [GuestySyncController::class, 'refreshBookingToken'])
        ->name('getBookingToken');

    /*
    |----------------------------------------------------------------------
    | Phase 7: Booking Enquiries
    |----------------------------------------------------------------------
    */
    // AJAX: calendar availability for create/edit datepickers
    Route::post('get-checkin-checkout-data-gaurav', [BookingRequestController::class, 'getCheckinCheckoutDataGaurav'])
        ->name('get-checkin-checkout-data-gaurav');

    // AJAX: quote calculation (admin booking create/edit views)
    Route::post('admin-checkajax-get-quote', [BookingRequestController::class, 'adminCheckAjaxGetQuoteData'])
        ->name('admin-checkajax-get-quote');
    Route::post('admin-checkajax-get-quote-edit', [BookingRequestController::class, 'adminCheckAjaxGetQuoteDataEdit'])
        ->name('admin-checkajax-get-quote-edit');

    // Must be registered BEFORE the resource route so they don't clash
    Route::get('booking-enquiries/confirmed/{id}', [BookingRequestController::class, 'confirmed'])
        ->name('booking-enquiry-confirm');
    Route::get('booking-enquiries/properties/{id}', [BookingRequestController::class, 'singlePropertyBookoing'])
        ->name('singlePropertyBookoing');

    Route::resource('booking-enquiries', BookingRequestController::class);
});
