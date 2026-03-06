<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LocationFormRequest;
use App\Models\Location;
use App\Repositories\Contracts\LocationRepositoryInterface;
use App\Services\Media\UploadService;
use App\Services\Shared\CrudService;

class LocationController extends Controller
{
    protected CrudService $service;

    protected string $routeIndex = 'locations.index';

    protected string $viewPrefix = 'admin.locations';

    protected array $imageFields = [
        'image'            => 'locations',
        'attraction_image' => 'locations',
        'bannerImage'      => 'locations',
    ];

    public function __construct(LocationRepositoryInterface $repository, UploadService $uploadService)
    {
        $this->service = new CrudService($repository, $uploadService);
    }

    public function index()
    {
        return view("{$this->viewPrefix}.index", [
            'data' => Location::orderBy('id', 'desc')->get(),
        ]);
    }

    public function create()
    {
        return view("{$this->viewPrefix}.create");
    }

    public function store(LocationFormRequest $request)
    {
        $this->service->store($request, $request->all(), $this->imageFields);

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

    public function update(LocationFormRequest $request, string $id)
    {
        $this->service->update($id, $request, $request->all(), $this->imageFields);

        return redirect()->route($this->routeIndex)->with('success', 'Successfully Updated');
    }

    public function destroy(string $id)
    {
        $this->service->destroy($id, array_keys($this->imageFields));

        return redirect()->route($this->routeIndex)->with('success', 'Successfully Deleted');
    }
}
