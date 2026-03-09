<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Cms;

/**
 * SitemapController — XML sitemap.
 *
 * Legacy: PageController::sitemap
 */
class SitemapController extends Controller
{
    public function index()
    {
        $cms            = Cms::all();
        $blogs          = Blog::all();
        $blogcategories = BlogCategory::all();

        return response()
            ->view('front.sitemap', compact('cms', 'blogs', 'blogcategories'))
            ->header('Content-Type', 'text/xml');
    }
}
