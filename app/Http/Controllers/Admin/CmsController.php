<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CmsFormRequest;
use App\Services\Content\CmsService;

class CmsController extends Controller
{
    protected string $routeIndex = 'cms.index';

    protected string $viewPrefix = 'admin.cms';

    public function __construct(
        protected CmsService $cmsService,
    ) {
    }

    public function index()
    {
        return view("{$this->viewPrefix}.index", [
            'data' => $this->cmsService->all(),
        ]);
    }

    public function create()
    {
        return view("{$this->viewPrefix}.create");
    }

    public function store(CmsFormRequest $request)
    {
        $this->cmsService->store($request, $request->all());

        return redirect()->route($this->routeIndex)->with('success', 'Successfully Added');
    }

    public function show(string $id)
    {
        return redirect()->route($this->routeIndex);
    }

    public function edit(string $id)
    {
        $data = $this->cmsService->find($id);

        if (! $data) {
            return redirect()->route($this->routeIndex)->with('danger', 'Invalid Calling');
        }

        return view("{$this->viewPrefix}.edit", compact('data'));
    }

    public function update(CmsFormRequest $request, string $id)
    {
        $this->cmsService->update($id, $request, $request->all());

        return redirect()->route($this->routeIndex)->with('success', 'Successfully Updated');
    }

    public function destroy(string $id)
    {
        $this->cmsService->destroy($id);

        return redirect()->route($this->routeIndex)->with('success', 'Successfully Deleted');
    }
}
