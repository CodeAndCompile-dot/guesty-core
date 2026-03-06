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

use App\Http\Controllers\Admin\CkeditorController;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\ContactusRequestController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmailTempleteController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\LandingCmsController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\MaximizeAssetController;
use App\Http\Controllers\Admin\NewsLetterController;
use App\Http\Controllers\Admin\OnboardingRequestController;
use App\Http\Controllers\Admin\OurClientController;
use App\Http\Controllers\Admin\OurTeamController;
use App\Http\Controllers\Admin\PropertyAmenityController;
use App\Http\Controllers\Admin\PropertyAmenityGroupController;
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
    Route::post('medias-destroy', [DashboardController::class, 'mediasDelete'])->name('medias-destroy');

    // Bulk Delete
    Route::post('multipleDelete/{model}', [DashboardController::class, 'multipleDelete'])->name('multipleDelete');

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

    // Stub route for calendar refresh (Phase 6 — iCal). Avoids view crash in add-bar partial.
    Route::get('refresh-calendar-data', fn () => redirect()->back())->name('refresshCalendar');

    // Stub route for property calendar (Phase 6 — iCal). Avoids view crash in property index.
    Route::get('properties/{property_id}/calendar', fn ($property_id) => redirect()->route('properties.edit', $property_id))->name('properties-calendar.index');

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
});
