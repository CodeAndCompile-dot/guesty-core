<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AttractionCategoryFormRequest;
use App\Models\AttractionCategory;
use App\Repositories\Eloquent\GenericCrudRepository;
use App\Services\Media\UploadService;
use App\Services\Shared\CrudService;

class AttractionCategoryController extends Controller
{
    protected CrudService $service;

    protected string $routeIndex = 'attraction-categories.index';

    protected string $viewPrefix = 'admin.attraction-categories';

    protected array $imageFields = [
        'image'            => 'attraction-categories',
        'attraction_image' => 'attraction-categories',
        'bannerImage'      => 'attraction-categories',
    ];

    public function __construct(UploadService $uploadService)
    {
        $this->service = new CrudService(
            GenericCrudRepository::for(AttractionCategory::class),
            $uploadService,
        );
    }

    public function index()
    {
        return view("{$this->viewPrefix}.index", [
            'data' => AttractionCategory::orderBy('id', 'desc')->get(),
        ]);
    }

    public function create()
    {
        return view("{$this->viewPrefix}.create");
    }

    public function store(AttractionCategoryFormRequest $request)
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

    public function update(AttractionCategoryFormRequest $request, string $id)
    {
        // Handle remove-image checkbox fields (legacy behavior)
        $data = $request->all();
        $record = $this->service->findOrFail($id);

        $removeFields = [
            'remove_image'             => ['field' => 'image'],
            'remove_attraction_image'  => ['field' => 'attraction_image'],
            'remove_banner_image'      => ['field' => 'bannerImage'],
        ];

        foreach ($removeFields as $checkbox => $config) {
            if ($request->input($checkbox)) {
                $field = $config['field'];
                if (! empty($record->{$field})) {
                    $this->service->getUploadService()->delete($record->{$field});
                }
                $data[$field] = '';
            }
        }

        $this->service->update($id, $request, $data, $this->imageFields);

        return redirect()->route($this->routeIndex)->with('success', 'Successfully Updated');
    }

    public function destroy(string $id)
    {
        $this->service->destroy($id, array_keys($this->imageFields));

        return redirect()->route($this->routeIndex)->with('success', 'Successfully Deleted');
    }
}
