<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LandingCmsFormRequest;
use App\Models\LandingCms;
use App\Services\Content\SeoContentService;
use App\Services\Media\UploadService;

class LandingCmsController extends Controller
{
    protected SeoContentService $service;

    protected string $routeIndex = 'landing_cms.index';

    protected string $viewPrefix = 'admin.landing_cms';

    public function __construct(UploadService $uploadService)
    {
        $this->service = new SeoContentService(LandingCms::class, $uploadService);
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

    public function store(LandingCmsFormRequest $request)
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
        $data = $this->service->find($id);

        if (! $data) {
            return redirect()->route($this->routeIndex)->with('danger', 'Invalid Calling');
        }

        return view("{$this->viewPrefix}.edit", compact('data'));
    }

    public function update(LandingCmsFormRequest $request, string $id)
    {
        $this->service->update($id, $request, $request->all());

        return redirect()->route($this->routeIndex)->with('success', 'Successfully Updated');
    }

    public function destroy(string $id)
    {
        $this->service->destroy($id);

        return redirect()->route($this->routeIndex)->with('success', 'Successfully Deleted');
    }
}
