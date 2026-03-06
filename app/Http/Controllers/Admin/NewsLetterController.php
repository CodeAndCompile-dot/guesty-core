<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NewsLetterFormRequest;
use App\Models\NewsLetter;
use App\Repositories\Eloquent\GenericCrudRepository;
use App\Services\Media\UploadService;
use App\Services\Shared\CrudService;

class NewsLetterController extends Controller
{
    protected CrudService $service;

    protected string $routeIndex = 'newsletters.index';

    protected string $viewPrefix = 'admin.newsletters';

    protected array $imageFields = [
        'image' => 'newsletters',
        'bannerImage' => 'newsletters',
    ];

    public function __construct(UploadService $uploadService)
    {
        $this->service = new CrudService(
            GenericCrudRepository::for(NewsLetter::class),
            $uploadService,
        );
    }

    public function index()
    {
        return view("{$this->viewPrefix}.index", [
            'data' => NewsLetter::orderBy('id', 'desc')->get(),
        ]);
    }

    public function create()
    {
        return view("{$this->viewPrefix}.create");
    }

    public function store(NewsLetterFormRequest $request)
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

    public function update(NewsLetterFormRequest $request, string $id)
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
