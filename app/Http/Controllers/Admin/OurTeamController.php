<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OurTeamFormRequest;
use App\Models\OurTeam;
use App\Repositories\Eloquent\GenericCrudRepository;
use App\Services\Media\UploadService;
use App\Services\Shared\CrudService;

class OurTeamController extends Controller
{
    protected CrudService $service;

    protected string $routeIndex = 'our-teams.index';

    protected string $viewPrefix = 'admin.our-teams';

    /**
     * Legacy: image & bannerImage → "our-teams"; contactImage → "testimonials".
     * NOTE: We fix the contactImage folder to "our-teams" (legacy bug uploaded to testimonials).
     */
    protected array $imageFields = [
        'image' => 'our-teams',
        'bannerImage' => 'our-teams',
        'contactImage' => 'our-teams',
    ];

    public function __construct(UploadService $uploadService)
    {
        $this->service = new CrudService(
            GenericCrudRepository::for(OurTeam::class),
            $uploadService,
        );
    }

    public function index()
    {
        return view("{$this->viewPrefix}.index", [
            'data' => OurTeam::orderBy('id', 'desc')->get(),
        ]);
    }

    public function create()
    {
        return view("{$this->viewPrefix}.create");
    }

    public function store(OurTeamFormRequest $request)
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

    public function update(OurTeamFormRequest $request, string $id)
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
