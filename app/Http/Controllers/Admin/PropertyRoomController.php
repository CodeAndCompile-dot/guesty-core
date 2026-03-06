<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoomFormRequest;
use App\Models\Property;
use App\Services\Property\RoomService;

class PropertyRoomController extends Controller
{
    protected string $viewPrefix = 'admin.properties-group-rooms';

    public function __construct(
        protected RoomService $service,
    ) {
    }

    public function index(string $property_id)
    {
        $property = Property::findOrFail($property_id);

        return view("{$this->viewPrefix}.index", [
            'data'        => $this->service->getRoomsByProperty($property_id),
            'property_id' => $property_id,
            'property'    => $property,
            'name'        => 'Room',
        ]);
    }

    public function create(string $property_id)
    {
        $property = Property::findOrFail($property_id);

        return view("{$this->viewPrefix}.create", [
            'property_id' => $property_id,
            'property'    => $property,
            'name'        => 'Room',
        ]);
    }

    public function store(RoomFormRequest $request, string $property_id)
    {
        $this->service->storeRoom($property_id, $request->all());

        return redirect()->route('properties-group-rooms', $property_id)
            ->with('success', 'Successfully Added');
    }

    public function edit(string $property_id, string $id)
    {
        return view("{$this->viewPrefix}.edit", [
            'data'        => $this->service->findRoomOrFail($id),
            'property_id' => $property_id,
        ]);
    }

    public function update(RoomFormRequest $request, string $property_id, string $id)
    {
        $this->service->updateRoom($id, $request->all());

        return redirect()->route('properties-group-rooms', $property_id)
            ->with('success', 'Successfully Updated');
    }

    public function destroy(string $property_id, string $id)
    {
        $this->service->destroyRoom($id);

        return redirect()->route('properties-group-rooms', $property_id)
            ->with('success', 'Successfully Deleted');
    }

    public function active(string $property_id, string $id)
    {
        $this->service->activateRoom($id);

        return redirect()->route('properties-group-rooms', $property_id)
            ->with('success', 'Successfully Activated');
    }

    public function deactive(string $property_id, string $id)
    {
        $this->service->deactivateRoom($id);

        return redirect()->route('properties-group-rooms', $property_id)
            ->with('success', 'Successfully Deactivated');
    }
}
