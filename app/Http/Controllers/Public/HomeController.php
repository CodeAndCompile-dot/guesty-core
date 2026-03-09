<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Cms;

/**
 * HomeController — serves the public homepage.
 *
 * Legacy: PageController::index()
 */
class HomeController extends Controller
{
    public function index()
    {
        $data = Cms::where('seo_url', 'home')->first();

        if ($data && $data->templete === 'home') {
            return view('front.static.' . $data->templete, compact('data'));
        }

        abort(404);
    }
}
