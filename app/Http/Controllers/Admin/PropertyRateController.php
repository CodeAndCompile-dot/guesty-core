<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RateGroupFormRequest;
use App\Models\Property;
use App\Services\Property\RateService;
use Illuminate\Http\Request;

class PropertyRateController extends Controller
{
    protected string $viewPrefix = 'admin.properties-rates';

    public function __construct(
        protected RateService $service,
    ) {
    }

    public function index(string $property_id)
    {
        $property = Property::findOrFail($property_id);

        return view("{$this->viewPrefix}.index", [
            'data'        => $this->service->getByProperty($property_id),
            'property_id' => $property_id,
            'property'    => $property,
            'name'        => 'Rate',
        ]);
    }

    public function create(string $property_id)
    {
        $property = Property::findOrFail($property_id);

        return view("{$this->viewPrefix}.create", [
            'property_id' => $property_id,
            'property'    => $property,
            'name'        => 'Rate',
        ]);
    }

    public function store(RateGroupFormRequest $request, string $property_id)
    {
        $this->service->store($property_id, $request->all());

        return redirect()->route('properties-rates', $property_id)
            ->with('success', 'Successfully Added');
    }

    public function edit(string $property_id, string $id)
    {
        return view("{$this->viewPrefix}.edit", [
            'data'        => $this->service->findOrFail($id),
            'property_id' => $property_id,
        ]);
    }

    public function update(RateGroupFormRequest $request, string $property_id, string $id)
    {
        $this->service->update($id, $property_id, $request->all());

        return redirect()->route('properties-rates', $property_id)
            ->with('success', 'Successfully Updated');
    }

    public function destroy(string $property_id, string $id)
    {
        $this->service->destroy($id);

        return redirect()->route('properties-rates', $property_id)
            ->with('success', 'Successfully Deleted');
    }

    public function copyData(string $property_id, string $id)
    {
        $this->service->duplicate($id);

        return redirect()->route('properties-rates', $property_id)
            ->with('success', 'Successfully Duplicated');
    }
}
