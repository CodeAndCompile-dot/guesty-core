<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PropertyFormRequest;
use App\Services\Property\PropertyService;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    protected string $routeIndex = 'properties.index';

    protected string $viewPrefix = 'admin.properties';

    public function __construct(
        protected PropertyService $service,
    ) {
    }

    public function index()
    {
        return view("{$this->viewPrefix}.index", [
            'data' => $this->service->all(),
        ]);
    }

    public function create()
    {
        return view("{$this->viewPrefix}.create");
    }

    public function store(PropertyFormRequest $request)
    {
        $this->service->store($request, $request->all());

        return redirect()->route($this->routeIndex)->with('success', 'Successfully Added');
    }

    public function show(string $id)
    {
        return redirect()->route($this->routeIndex);
    }

    public function edit(string $id)
    {
        $data = $this->service->findOrFail($id);

        return view("{$this->viewPrefix}.edit", compact('data'));
    }

    public function update(PropertyFormRequest $request, string $id)
    {
        $this->service->update($id, $request, $request->all());

        return redirect()->route($this->routeIndex)->with('success', 'Successfully Updated');
    }

    public function destroy(string $id)
    {
        $this->service->destroy($id);

        return redirect()->route($this->routeIndex)->with('success', 'Successfully Deleted');
    }

    public function copyData(string $id)
    {
        $this->service->duplicate($id);

        return redirect()->route($this->routeIndex)->with('success', 'Successfully Duplicated');
    }

    public function active(string $id)
    {
        $this->service->activate($id);

        return redirect()->route($this->routeIndex)->with('success', 'Successfully Activated');
    }

    public function deactive(string $id)
    {
        $this->service->deactivate($id);

        return redirect()->route($this->routeIndex)->with('success', 'Successfully Deactivated');
    }

    /**
     * AJAX: Update gallery caption and sorting.
     */
    public function updateCaptionSort(Request $request)
    {
        $this->service->updateCaptionSort(
            $request->input('captionidsarray', []),
            $request->input('captionidsarray_sorting', []),
            $request->input('captionidsarray_caption', []),
        );

        return response()->json(['success' => true]);
    }

    /**
     * AJAX: Delete a single gallery image.
     */
    public function imageDeleteAsset(Request $request)
    {
        $this->service->deleteGalleryImage($request->input('id'));

        return response()->json(['success' => true]);
    }

    /**
     * AJAX: Delete a single property space.
     */
    public function deletePropertySpace(Request $request)
    {
        $this->service->deleteSpace($request->input('id'));

        return response()->json(['success' => true]);
    }
}
