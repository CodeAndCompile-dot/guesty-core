<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogFormRequest;
use App\Models\Blog;
use App\Repositories\Eloquent\GenericCrudRepository;
use App\Services\Media\UploadService;
use App\Services\Shared\CrudService;

class BlogController extends Controller
{
    protected CrudService $service;

    protected string $routeIndex = 'blogs.index';

    protected string $viewPrefix = 'admin.blogs';

    protected array $imageFields = [
        'image'        => 'blogs',
        'featureImage' => 'blogs',
    ];

    public function __construct(UploadService $uploadService)
    {
        $this->service = new CrudService(
            GenericCrudRepository::for(Blog::class),
            $uploadService,
        );
    }

    public function index()
    {
        return view("{$this->viewPrefix}.index", [
            'data' => Blog::orderBy('id', 'desc')->get(),
        ]);
    }

    public function create()
    {
        return view("{$this->viewPrefix}.create");
    }

    public function store(BlogFormRequest $request)
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

    public function update(BlogFormRequest $request, string $id)
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
     * Activate a blog (set status = 'active').
     */
    public function active(string $id)
    {
        $blog = Blog::findOrFail($id);
        $blog->status = 'active';
        $blog->save();

        return redirect()->route($this->routeIndex)->with('success', 'Successfully active');
    }

    /**
     * Deactivate a blog (set status = 'deactive').
     */
    public function deactive(string $id)
    {
        $blog = Blog::findOrFail($id);
        $blog->status = 'deactive';
        $blog->save();

        return redirect()->route($this->routeIndex)->with('success', 'Successfully deactive');
    }

    /**
     * Duplicate a blog record.
     */
    public function copyData(string $id)
    {
        $original = Blog::findOrFail($id);
        $clone = $original->replicate();
        $clone->seo_url = $original->seo_url . '-copy';
        $clone->created_at = now();
        $clone->save();

        return redirect()->route($this->routeIndex)->with('success', 'Successfully Coppied');
    }
}
