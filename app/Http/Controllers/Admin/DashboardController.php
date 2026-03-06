<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\SettingService;
use App\Services\Admin\UserService;
use App\Services\Media\MediaCenterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        protected SettingService $settingService,
        protected UserService $userService,
        protected MediaCenterService $mediaCenterService,
    ) {
    }

    /**
     * Dashboard index — redirect to guesty properties.
     */
    public function index()
    {
        return redirect('/client-login/guesty_properties');
    }

    /**
     * Show settings page.
     */
    public function setting()
    {
        return view('admin.dashboard.setting');
    }

    /**
     * Save settings (key-value upsert).
     */
    public function settingPost(Request $request)
    {
        $this->settingService->saveSettings($request);

        return redirect()->back()->with('success', 'Setting updated successfully');
    }

    /**
     * Show media center — list all files.
     */
    public function mediaCenter()
    {
        $data = $this->mediaCenterService->listFiles();

        return view('admin.dashboard.medias', compact('data'));
    }

    /**
     * Upload new files to media center.
     */
    public function newFileUploads(Request $request)
    {
        if ($request->hasFile('file')) {
            $this->mediaCenterService->uploadFiles($request->file('file'));
        }

        return redirect()->back();
    }

    /**
     * Delete a single media file.
     */
    public function mediasDelete(Request $request)
    {
        $this->mediaCenterService->deleteFile($request->input('file'));

        return redirect()->back()->with('success', 'Media deleted successfully');
    }

    /**
     * Bulk delete records by model type.
     */
    public function multipleDelete(Request $request, string $model)
    {
        $modelMap = [
            'galleries' => \App\Models\Gallery::class,
            'testimonials' => \App\Models\Testimonial::class,
            'faqs' => \App\Models\Faq::class,
            'sliders' => \App\Models\Slider::class,
            'contact-us-enquiries' => \App\Models\ContactusRequest::class,
            'newsletters' => \App\Models\NewsLetter::class,
        ];

        $modelClass = $modelMap[$model] ?? null;

        if ($modelClass && $request->has('id')) {
            $this->mediaCenterService->bulkDelete($modelClass, $request->input('id'));
        }
    }

    /**
     * Show change password page.
     */
    public function changePassword()
    {
        return view('admin.dashboard.changePassword');
    }

    /**
     * Process password change.
     */
    public function changePasswordPost(Request $request)
    {
        $user = Auth::user();

        $result = $this->userService->changePassword(
            $user,
            $request->input('old_password'),
            $request->input('new_password'),
        );

        if ($result) {
            return redirect()->back()->with('success', 'Password updated successfully');
        }

        return redirect()->back()->with('danger', 'Password Wrong');
    }
}
