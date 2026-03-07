<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogCategoryFormRequest;
use App\Models\BlogCategory;
use App\Repositories\Eloquent\GenericCrudRepository;
use App\Services\Media\UploadService;
use App\Services\Shared\CrudService;

class BlogCategoryController extends Controller
{
    protected CrudService $service;

    protected string $routeIndex = 'blog-category.index';

    protected string $viewPrefix = 'admin.blog-category';

    protected array $imageFields = [
        'image'       => 'blog-category',
        'bannerImage' => 'blog-category',
    ];

    public function __construct(UploadService $uploadService)
    {
        $this->service = new CrudService(
            GenericCrudRepository::for(BlogCategory::class),
            $uploadService,
        );
    }

    public function index()
    {
        return view("{$this->viewPrefix}.index", [
            'data' => BlogCategory::orderBy('id', 'desc')->get(),
        ]);
    }

    public function create()
    {
        return view("{$this->viewPrefix}.create");
    }

    public function store(BlogCategoryFormRequest $request)
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

    public function update(BlogCategoryFormRequest $request, string $id)
    {
        $this->service->update($id, $request, $request->all(), $this->imageFields);

        return redirect()->route($this->routeIndex)->with('success', 'Successfully Updated');
    }

    public function destroy(string $id)
    {
        $this->service->destroy($id, array_keys($this->imageFields));

        return redirect()->route($this->routeIndex)->with('success', 'Successfully Deleted');
    }

    /**
     * Duplicate a blog category record.
     */
    public function copyData(string $id)
    {
        $original = BlogCategory::findOrFail($id);
        $clone = $original->replicate();
        $clone->seo_url = $original->seo_url . '-copy';
        $clone->created_at = now();
        $clone->save();

        return redirect()->route($this->routeIndex)->with('success', 'Successfully Coppied');
    }
}
