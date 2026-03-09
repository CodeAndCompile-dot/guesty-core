<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Attraction;
use App\Models\AttractionCategory;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Cms;
use App\Models\GuestyProperty;
use App\Models\LandingCms;
use App\Models\Location;
use App\Models\OurTeam;
use App\Models\SeoCms;
use Illuminate\Http\Request;

/**
 * PageController — serves public CMS pages, blog, attractions, and dynamic slug resolution.
 *
 * Legacy: Multiple methods from PageController (dynamicDataCategory, ourTeamSingle,
 *         getVacationData, blogSingle, categoryData, attractionSingle, etc.)
 */
class PageController extends Controller
{
    /**
     * Dynamic page resolver — cascades through Cms → LandingCms → GuestyProperty.
     *
     * Legacy: PageController::dynamicDataCategory()
     */
    public function cmsPage(Request $request, string $seo_url)
    {
        if ($seo_url === 'home') {
            return redirect('/');
        }

        // --- Cms lookup ---
        $data = Cms::where('seo_url', $seo_url)->first();

        if ($data) {
            $template = 'front.static.' . $data->templete;
            $ogimage  = $data->ogimage ?? '';

            if ($data->templete === 'blogs') {
                $blogs = Blog::orderBy('id', 'desc')->paginate(12);

                return view($template, compact('data', 'blogs'))->with('ogimage', $ogimage);
            }

            if ($data->templete === 'get-quote') {
                return $this->handleGetQuote($request, $template, $data, $ogimage);
            }

            return view($template, compact('data'))->with('ogimage', $ogimage);
        }

        // --- LandingCms lookup ---
        $data = LandingCms::where('seo_url', $seo_url)->first();

        if ($data) {
            return view('front.landing-pages.' . $data->templete, compact('data'));
        }

        // --- GuestyProperty slug lookup ---
        $data = GuestyProperty::where('seo_url', $seo_url)->first();

        if ($data) {
            $ogimage = $data->ogimage ?? '';

            return view('front.property.singleGuesty', compact('data'))->with('ogimage', $ogimage);
        }

        abort(404);
    }

    /**
     * Single team member page.
     *
     * Legacy: PageController::ourTeamSingle()
     */
    public function teamMember(string $seo_url)
    {
        $data = OurTeam::where('seo_url', $seo_url)->firstOrFail();

        return view('front.meet-the-teams.common', compact('data'));
    }

    /**
     * Vacation / SEO page.
     *
     * Legacy: PageController::getVacationData()
     */
    public function vacation(string $seo_url)
    {
        $data = SeoCms::where('seo_url', $seo_url)->firstOrFail();

        return view('front.seo-pages.' . $data->templete, compact('data'));
    }

    /**
     * Single blog post.
     *
     * Legacy: PageController::blogSingle()
     */
    public function blogSingle(string $seo_url)
    {
        $data = Blog::where('seo_url', $seo_url)->firstOrFail();

        $category = BlogCategory::find($data->blog_category_id);

        return view('front.group.single', compact('data', 'category'));
    }

    /**
     * Blog category listing.
     *
     * Legacy: PageController::categoryData()
     */
    public function blogCategory(string $seo_url)
    {
        $data = BlogCategory::where('seo_url', $seo_url)->firstOrFail();

        $blogs = Blog::where('blog_category_id', $data->id)
            ->orderBy('id', 'desc')
            ->paginate(12);

        return view('front.group.category', compact('data', 'blogs'));
    }

    /**
     * Single attraction.
     *
     * Legacy: PageController::attractionSingle()
     */
    public function attractionSingle(string $seo_url)
    {
        $data = Attraction::where('seo_url', $seo_url)->firstOrFail();

        return view('front.attractions.single', compact('data'));
    }

    /**
     * Attraction category listing.
     *
     * Legacy: PageController::attractionCategory()
     */
    public function attractionCategory(string $seo_url)
    {
        $data = AttractionCategory::where('seo_url', $seo_url)->firstOrFail();

        return view('front.attractions.category', compact('data'));
    }

    /**
     * Attractions by location.
     *
     * Legacy: PageController::attractionLocation()
     */
    public function attractionLocation(string $seo_url)
    {
        $data = Location::where('seo_url', $seo_url)->firstOrFail();

        return view('front.attractions.location', compact('data'));
    }

    /**
     * Properties by location.
     *
     * Legacy: PageController::propertyLocation()
     */
    public function propertyLocation(string $seo_url)
    {
        $data = Location::where('seo_url', $seo_url)->firstOrFail();

        return view('front.property.location', compact('data'));
    }

    /* ------------------------------------------------------------------ */
    /*  Private helpers                                                     */
    /* ------------------------------------------------------------------ */

    /**
     * Handle the get-quote CMS template (Guesty quote flow).
     */
    private function handleGetQuote(Request $request, string $template, Cms $data, string $ogimage)
    {
        if (! $request->property_id) {
            return redirect()->back()->with('danger', 'Invalid Property');
        }

        $property = GuestyProperty::find($request->property_id);

        if (! $property) {
            return redirect()->back()->with('danger', 'Invalid Property');
        }

        if (! $request->start_date) {
            return redirect($property->seo_url)->with('danger', 'Invalid Checkin');
        }

        if (! $request->end_date) {
            return redirect($property->seo_url)->with('danger', 'Invalid Checkout');
        }

        $totalGuest = (int) $request->get('adults', 0) + (int) $request->get('child', 0);

        if ($property->accommodates < $totalGuest) {
            return redirect($property->seo_url)
                ->with('danger', 'Guest limit cannot exceed ' . $property->accommodates);
        }

        $coupon = $request->get('coupon', 'default');

        // Check availability via Helper
        $availability = \Helper::getGrossDataCheckerDays($property, $request->start_date, $request->end_date);

        if ($availability && ($availability['status'] ?? 200) !== 200) {
            return redirect($property->seo_url)->with('danger', $availability['message'] ?? 'Not available');
        }

        // Get Guesty quote
        $guestyapi = \GuestyApi::getQuoteNewNew(
            $totalGuest,
            (int) $request->get('adults', 0),
            (int) $request->get('child', 0),
            $request->start_date,
            $request->end_date,
            $property->_id,
            $coupon,
        );

        if (! $guestyapi || ($guestyapi['status'] ?? 0) !== 200) {
            $message = $guestyapi['message'] ?? 'Something happened';

            return redirect($property->seo_url)->with('danger', $message);
        }

        $main_data = [
            'guestyapi'               => $guestyapi,
            'total_guests'            => $totalGuest,
            'adults'                  => $request->get('adults'),
            'child'                   => $request->get('child'),
            'start_date'              => $request->get('start_date'),
            'end_date'                => $request->get('end_date'),
            'childs'                  => $request->get('child'),
            'pet_fee_data_guarav'     => $request->get('no_of_pets'),
            'heating_pool_fee'        => $request->get('heating_pool_fee'),
        ];

        return view($template, compact('data', 'main_data', 'property'))->with('ogimage', $ogimage);
    }
}
