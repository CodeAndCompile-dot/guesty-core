<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GuestyPropertyFormRequest;
use App\Models\GuestyProperty;
use App\Models\Location;
use App\Repositories\Eloquent\GenericCrudRepository;
use App\Services\Media\UploadService;
use App\Services\Shared\CrudService;
use Illuminate\Http\Request;

class GuestyPropertyController extends Controller
{
    protected CrudService $service;

    public function __construct(UploadService $uploadService)
    {
        $this->service = new CrudService(
            GenericCrudRepository::for(GuestyProperty::class),
            $uploadService,
        );
    }

    public function index()
    {
        $data = GuestyProperty::orderBy('id', 'desc')->get();

        return view('admin.guesty_properties.index', compact('data'));
    }

    public function create()
    {
        return redirect()->route('guesty_properties.index');
    }

    public function store(Request $request)
    {
        return redirect()->route('guesty_properties.index');
    }

    public function show(string $id)
    {
        return redirect()->route('guesty_properties.index');
    }

    public function edit(string $id)
    {
        $data = $this->service->find($id);

        if (! $data) {
            return redirect()->route('guesty_properties.index')->with('danger', 'Invalid Calling');
        }

        return view('admin.guesty_properties.edit', compact('data'));
    }

    public function update(GuestyPropertyFormRequest $request, string $id)
    {
        $existing = $this->service->find($id);

        if (! $existing) {
            return redirect()->back()->with('danger', 'Invalid Calling');
        }

        $imageFields = [
            'banner_image'               => 'properties',
            'booklet'                    => 'properties',
            'feature_image'              => 'properties',
            'ogimage'                    => 'properties',
            'rental_aggrement_attachment' => 'properties',
        ];

        $data = $request->all();

        // Handle remove-banner-image flag (legacy compatibility)
        if ($request->remove_banner_image) {
            $data['banner_image'] = '';
        }

        $this->service->update($id, $request, $data, $imageFields);

        return redirect()->route('guesty_properties.index')->with('success', 'Successfully Updated');
    }

    public function destroy(string $id)
    {
        return redirect()->route('guesty_properties.index')->with('danger', 'Invalid Calling');
    }

    /**
     * AJAX: Return sub-locations for a given parent location.
     * Legacy compatibility: returns raw HTML options.
     */
    public function getSubLocationList(Request $request)
    {
        $html = "<option value=''>Select Sub Location</option>";

        if ($request->id) {
            foreach (Location::where('is_parent', $request->id)->get() as $location) {
                $html .= "<option value='{$location->id}'>{$location->name}</option>";
            }
        }

        return $html;
    }
}
