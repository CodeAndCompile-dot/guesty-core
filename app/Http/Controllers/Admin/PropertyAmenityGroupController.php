<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AmenityGroupFormRequest;
use App\Models\Property;
use App\Services\Property\AmenityService;

class PropertyAmenityGroupController extends Controller
{
    protected string $viewPrefix = 'admin.properties-group-amenities';

    public function __construct(
        protected AmenityService $service,
    ) {
    }

    public function index(string $property_id)
    {
        $property = Property::findOrFail($property_id);

        return view("{$this->viewPrefix}.index", [
            'data'        => $this->service->getGroupsByProperty($property_id),
            'property_id' => $property_id,
            'property'    => $property,
            'name'        => 'Amenity Group',
        ]);
    }

    public function create(string $property_id)
    {
        $property = Property::findOrFail($property_id);

        return view("{$this->viewPrefix}.create", [
            'property_id' => $property_id,
            'property'    => $property,
            'name'        => 'Amenity Group',
        ]);
    }

    public function store(AmenityGroupFormRequest $request, string $property_id)
    {
        $this->service->storeGroup($property_id, $request, $request->all());

        return redirect()->route('properties.edit', $property_id)
            ->with('success', 'Successfully Added');
    }

    public function edit(string $property_id, string $id)
    {
        return view("{$this->viewPrefix}.edit", [
            'data'        => $this->service->findGroupOrFail($id),
            'property_id' => $property_id,
        ]);
    }

    public function update(AmenityGroupFormRequest $request, string $property_id, string $id)
    {
        $this->service->updateGroup($id, $request, $request->all());

        return redirect()->route('properties.edit', $property_id)
            ->with('success', 'Successfully Updated');
    }

    public function destroy(string $property_id, string $id)
    {
        $this->service->destroyGroup($id);

        return redirect()->route('properties.edit', $property_id)
            ->with('success', 'Successfully Deleted');
    }

    public function active(string $property_id, string $id)
    {
        $this->service->activateGroup($id);

        return redirect()->route('properties.edit', $property_id)
            ->with('success', 'Successfully Activated');
    }

    public function deactive(string $property_id, string $id)
    {
        $this->service->deactivateGroup($id);

        return redirect()->route('properties.edit', $property_id)
            ->with('success', 'Successfully Deactivated');
    }
}
