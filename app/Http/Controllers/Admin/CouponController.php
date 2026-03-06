<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CouponFormRequest;
use App\Models\Coupon;
use App\Repositories\Contracts\CouponRepositoryInterface;
use App\Services\Media\UploadService;
use App\Services\Shared\CrudService;

class CouponController extends Controller
{
    protected CrudService $service;

    protected string $routeIndex = 'coupons.index';

    protected string $viewPrefix = 'admin.coupons';

    public function __construct(CouponRepositoryInterface $repository, UploadService $uploadService)
    {
        $this->service = new CrudService($repository, $uploadService);
    }

    public function index()
    {
        return view("{$this->viewPrefix}.index", [
            'data' => Coupon::orderBy('id', 'desc')->get(),
        ]);
    }

    public function create()
    {
        return view("{$this->viewPrefix}.create");
    }

    public function store(CouponFormRequest $request)
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
        $data = $this->service->findOrFail($id);

        return view("{$this->viewPrefix}.edit", compact('data'));
    }

    public function update(CouponFormRequest $request, string $id)
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
