<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplete;
use App\Repositories\Eloquent\GenericCrudRepository;
use App\Services\Media\UploadService;
use App\Services\Shared\CrudService;
use Illuminate\Http\Request;

class EmailTempleteController extends Controller
{
    protected CrudService $service;

    protected string $routeIndex = 'email-templetes.index';

    protected string $viewPrefix = 'admin.email-templetes';

    protected array $imageFields = [
        'image' => 'email-templetes',
        'bannerImage' => 'email-templetes',
    ];

    public function __construct(UploadService $uploadService)
    {
        $this->service = new CrudService(
            GenericCrudRepository::for(EmailTemplete::class),
            $uploadService,
        );
    }

    public function index()
    {
        return view("{$this->viewPrefix}.index", [
            'data' => EmailTemplete::orderBy('id', 'desc')->get(),
        ]);
    }

    public function create()
    {
        return view("{$this->viewPrefix}.create");
    }

    public function store(Request $request)
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

    public function update(Request $request, string $id)
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
