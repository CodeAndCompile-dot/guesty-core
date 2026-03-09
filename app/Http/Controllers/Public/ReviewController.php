<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\ReviewFormRequest;
use App\Models\Testimonial;

/**
 * ReviewController — guest review submission.
 *
 * Legacy: PageController::reviewSubmit
 */
class ReviewController extends Controller
{
    public function store(ReviewFormRequest $request)
    {
        Testimonial::create($request->validated());

        return redirect()->back()->with('success', 'Thank you for submitting your review');
    }
}
