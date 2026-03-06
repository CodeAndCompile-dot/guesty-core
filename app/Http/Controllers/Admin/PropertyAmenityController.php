<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AmenityFormRequest;
use App\Services\Property\AmenityService;

class PropertyAmenityController extends Controller
{
    protected string $viewPrefix = 'admin.properties-amenities';

    public function __construct(
        protected AmenityService $service,
    ) {
    }

    public function index(string $property_id, string $group_id)
    {
        return view("{$this->viewPrefix}.index", [
            'data'        => $this->service->getAmenitiesByGroup($group_id),
            'property_id' => $property_id,
            'group_id'    => $group_id,
        ]);
    }

    public function create(string $property_id, string $group_id)
    {
        return view("{$this->viewPrefix}.create", [
            'property_id' => $property_id,
            'group_id'    => $group_id,
        ]);
    }

    public function store(AmenityFormRequest $request, string $property_id, string $group_id)
    {
        $this->service->storeAmenity($group_id, $request, $request->all());

        return redirect()->route('properties-amenities', [$property_id, $group_id])
            ->with('success', 'Successfully Added');
    }

    public function edit(string $property_id, string $group_id, string $id)
    {
        return view("{$this->viewPrefix}.edit", [
            'data'        => $this->service->findAmenityOrFail($id),
            'property_id' => $property_id,
            'group_id'    => $group_id,
        ]);
    }

    public function update(AmenityFormRequest $request, string $property_id, string $group_id, string $id)
    {
        $this->service->updateAmenity($id, $request, $request->all());

        return redirect()->route('properties-amenities', [$property_id, $group_id])
            ->with('success', 'Successfully Updated');
    }

    public function destroy(string $property_id, string $group_id, string $id)
    {
        $this->service->destroyAmenity($id);

        return redirect()->route('properties-amenities', [$property_id, $group_id])
            ->with('success', 'Successfully Deleted');
    }

    public function active(string $property_id, string $group_id, string $id)
    {
        $this->service->activateAmenity($id);

        return redirect()->route('properties-amenities', [$property_id, $group_id])
            ->with('success', 'Successfully Activated');
    }

    public function deactive(string $property_id, string $group_id, string $id)
    {
        $this->service->deactivateAmenity($id);

        return redirect()->route('properties-amenities', [$property_id, $group_id])
            ->with('success', 'Successfully Deactivated');
    }

    public function copyData(string $property_id, string $group_id, string $id)
    {
        $this->service->duplicateAmenity($id);

        return redirect()->route('properties-amenities', [$property_id, $group_id])
            ->with('success', 'Successfully Duplicated');
    }
}
