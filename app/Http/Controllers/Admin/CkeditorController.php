<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CkeditorController extends Controller
{
    /**
     * CKEditor index page.
     */
    public function index()
    {
        return view('ckeditor');
    }

    /**
     * Handle CKEditor image upload.
     *
     * Returns a JavaScript response that calls CKEditor's callback function
     * (legacy behavior — raw script response).
     */
    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filename = "{$originalName}_" . time() . ".{$extension}";

            $file->move(public_path('uploads'), $filename);

            $funcNum = $request->input('CKEditorFuncNum');
            $url = asset("uploads/{$filename}");
            $message = 'Image uploaded successfully';

            return response(
                "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction({$funcNum}, '{$url}', '{$message}');</script>"
            );
        }

        return response('No file uploaded', 400);
    }
}
