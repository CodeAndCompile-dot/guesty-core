<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Repositories\Eloquent\GenericCrudRepository;
use App\Services\Media\UploadService;
use App\Services\Shared\CrudService;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    protected CrudService $service;

    protected string $routeIndex = 'galleries.index';

    protected string $viewPrefix = 'admin.galleries';

    protected array $imageFields = [
        'image' => 'galleries',
        'bannerImage' => 'galleries',
    ];

    public function __construct(UploadService $uploadService)
    {
        $this->service = new CrudService(
            GenericCrudRepository::for(Gallery::class),
            $uploadService,
        );
    }

    public function index()
    {
        return view("{$this->viewPrefix}.index", [
            'data' => Gallery::orderBy('id', 'desc')->get(),
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

    public function copyData(string $id)
    {
        $this->service->duplicate($id);

        return redirect()->route($this->routeIndex)->with('success', 'Successfully Coppied');
    }

    public function active(string $id)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->status = 'active';
        $gallery->save();

        return redirect()->route($this->routeIndex)->with('success', 'Successfully active');
    }

    public function deactive(string $id)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->status = 'deactive';
        $gallery->save();

        return redirect()->route($this->routeIndex)->with('success', 'Successfully deactive');
    }
}
