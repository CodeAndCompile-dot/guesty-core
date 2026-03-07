<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IcalEvent;
use App\Models\IcalImportList;
use App\Models\Property;
use App\Services\Calendar\ICalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Admin controller for managing iCal import feeds per property.
 * Ports legacy PropertyCalendarController with identical behaviour.
 */
class PropertyCalendarController extends Controller
{
    protected string $adminBaseUrl = 'properties-calendar';

    protected string $adminView = 'admin.properties-calendar';

    public function __construct(
        protected ICalService $icalService,
    ) {}

    /**
     * List all iCal events for a property.
     */
    public function index(int $property_id)
    {
        $property = Property::find($property_id);

        if (! $property) {
            return redirect()->route('properties.index');
        }

        $data = IcalEvent::where('event_pid', $property_id)
            ->orderBy('id', 'desc')
            ->get();

        return view($this->adminView.'.index', compact('data', 'property_id', 'property'));
    }

    /**
     * Show form to add a new iCal import link.
     */
    public function create(int $property_id)
    {
        $property = Property::find($property_id);

        if (! $property) {
            return redirect()->route('properties.index');
        }

        return view($this->adminView.'.create', compact('property_id', 'property'));
    }

    /**
     * Store a new iCal import link and immediately refresh events.
     */
    public function store(Request $request, int $property_id)
    {
        $property = Property::find($property_id);

        if (! $property) {
            return redirect()->route('properties.index');
        }

        $validator = Validator::make($request->all(), [
            'ical_link' => 'required|unique:ical_import_list,ical_link',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->with('danger', $validator->errors()->first())
                ->withErrors($validator->errors());
        }

        $data = $request->all();
        $data['property_id'] = $property_id;

        $icalList = IcalImportList::create($data);

        $this->icalService->refreshImport($property_id, $icalList->ical_link, $icalList->id);

        return redirect()
            ->route($this->adminBaseUrl.'.index', [$property_id])
            ->with('success', 'Successfully Added');
    }

    /**
     * List all iCal import links for a property.
     */
    public function importlist(int $property_id)
    {
        $property = Property::find($property_id);

        if (! $property) {
            return redirect()->route('properties.index');
        }

        $data = IcalImportList::where('property_id', $property_id)
            ->orderBy('id', 'desc')
            ->get();

        return view($this->adminView.'.importlist', compact('data', 'property_id', 'property'));
    }

    /**
     * Refresh events for a specific import link.
     */
    public function importlistRefresh(int $property_id, int $id)
    {
        $property = Property::find($property_id);

        if (! $property) {
            return redirect()->back()->with('danger', 'invalid calling');
        }

        $icalList = IcalImportList::find($id);

        if (! $icalList) {
            return redirect()->back()->with('danger', 'invalid calling');
        }

        $this->icalService->refreshImport($property_id, $icalList->ical_link, $icalList->id);

        return redirect()->back()->with('success', 'Successfully Refreshed');
    }

    /**
     * Refresh the self-hosted .ics export file for a property.
     */
    public function selfIcalRefresh(int $id)
    {
        $this->icalService->exportPropertyIcs($id);

        return redirect()->back()->with('success', 'successfully refresh self ical');
    }

    /**
     * Delete an iCal import link and all associated events.
     */
    public function destroy(int $property_id, int $id)
    {
        $property = Property::find($property_id);

        if (! $property) {
            return redirect()->route('properties.index');
        }

        $exist = IcalImportList::find($id);

        if (! $exist) {
            return redirect()->back()->with('danger', 'Invalid Calling');
        }

        IcalEvent::where('ical_link', $exist->ical_link)->delete();
        $exist->delete();

        return redirect()->back()->with('success', 'Successfully Deleted');
    }
}
