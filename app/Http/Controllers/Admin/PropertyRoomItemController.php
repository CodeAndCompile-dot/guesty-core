<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Property\RoomService;
use Illuminate\Http\Request;

class PropertyRoomItemController extends Controller
{
    protected string $viewPrefix = 'admin.properties-sub-room';

    public function __construct(
        protected RoomService $service,
    ) {
    }

    public function index(string $property_id, string $group_id)
    {
        return view("{$this->viewPrefix}.index", [
            'data'        => $this->service->getItemsByRoom($group_id),
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

    public function store(Request $request, string $property_id, string $group_id)
    {
        $this->service->storeItem($group_id, $request->all());

        return redirect()->route('properties-sub-room', [$property_id, $group_id])
            ->with('success', 'Successfully Added');
    }

    public function edit(string $property_id, string $group_id, string $id)
    {
        return view("{$this->viewPrefix}.edit", [
            'data'        => $this->service->findItemOrFail($id),
            'property_id' => $property_id,
            'group_id'    => $group_id,
        ]);
    }

    public function update(Request $request, string $property_id, string $group_id, string $id)
    {
        $this->service->updateItem($id, $request->all());

        return redirect()->route('properties-sub-room', [$property_id, $group_id])
            ->with('success', 'Successfully Updated');
    }

    public function destroy(string $property_id, string $group_id, string $id)
    {
        $this->service->destroyItem($id);

        return redirect()->route('properties-sub-room', [$property_id, $group_id])
            ->with('success', 'Successfully Deleted');
    }

    public function active(string $property_id, string $group_id, string $id)
    {
        $this->service->activateItem($id);

        return redirect()->route('properties-sub-room', [$property_id, $group_id])
            ->with('success', 'Successfully Activated');
    }

    public function deactive(string $property_id, string $group_id, string $id)
    {
        $this->service->deactivateItem($id);

        return redirect()->route('properties-sub-room', [$property_id, $group_id])
            ->with('success', 'Successfully Deactivated');
    }
}
