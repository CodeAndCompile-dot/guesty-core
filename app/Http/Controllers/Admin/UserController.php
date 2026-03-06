<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserFormRequest;
use App\Models\User;
use App\Services\Admin\UserService;
use App\Services\Media\UploadService;
use App\Support\Traits\HasImageUpload;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use HasImageUpload;

    protected string $routeIndex = 'users.index';

    protected string $viewPrefix = 'admin.users';

    protected array $imageFields = [
        'image' => 'users',
        'bannerImage' => 'users',
    ];

    public function __construct(
        protected UserService $userService,
        protected UploadService $uploadService,
    ) {
    }

    public function index()
    {
        return view("{$this->viewPrefix}.index", [
            'data' => User::orderBy('id', 'desc')->get(),
        ]);
    }

    public function create()
    {
        return view("{$this->viewPrefix}.create");
    }

    public function store(UserFormRequest $request)
    {
        $data = $request->all();
        $data = $this->processImageUploads($request, $data, $this->imageFields);

        $this->userService->createUser($data);

        return redirect()->route($this->routeIndex)->with('success', 'Successfully Added');
    }

    public function show(string $id)
    {
        return redirect()->route($this->routeIndex);
    }

    public function edit(string $id)
    {
        $data = User::findOrFail($id);

        return view("{$this->viewPrefix}.edit", compact('data'));
    }

    public function update(UserFormRequest $request, string $id)
    {
        $data = $request->all();
        $existing = User::findOrFail($id);

        $existingImages = [];
        foreach (array_keys($this->imageFields) as $field) {
            $existingImages[$field] = $existing->{$field} ?? null;
        }
        $data = $this->processImageUploads($request, $data, $this->imageFields, $existingImages);

        $this->userService->updateUser($id, $data);

        return redirect()->route($this->routeIndex)->with('success', 'Successfully Updated');
    }

    public function destroy(string $id)
    {
        $this->userService->deleteUser($id);

        return redirect()->route($this->routeIndex)->with('success', 'Successfully Deleted');
    }
}
