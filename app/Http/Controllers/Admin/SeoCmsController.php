<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SeoCmsFormRequest;
use App\Models\SeoCms;
use App\Services\Content\SeoContentService;
use App\Services\Media\UploadService;

class SeoCmsController extends Controller
{
    protected SeoContentService $service;

    protected string $routeIndex = 'seo_pages.index';

    protected string $viewPrefix = 'admin.seo_pages';

    public function __construct(UploadService $uploadService)
    {
        $this->service = new SeoContentService(SeoCms::class, $uploadService);
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

    public function store(SeoCmsFormRequest $request)
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

    public function update(SeoCmsFormRequest $request, string $id)
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
