<?php

namespace App\Services\Content;

use App\Models\Cms;
use App\Repositories\Contracts\CmsRepositoryInterface;
use App\Services\Media\UploadService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CmsService
{
    /**
     * All image fields used in CMS CRUD, mapped to upload folder.
     */
    protected array $imageFields = [
        'image' => 'cms',
        'bannerImage' => 'cms',
        'ogimage' => 'cms',
        'image_2' => 'cms',
        'image_3' => 'cms',
        'faq_image' => 'cms',
        'strip_image' => 'cms',
        'about_image1' => 'cms',
        'about_image2' => 'cms',
        'owner_image' => 'cms',
        'section_image' => 'cms',
        'section2_img' => 'cms',
        'section4_main_img' => 'cms',
        'section4_sub_icon1' => 'cms',
        'section4_sub_icon2' => 'cms',
        'section4_sub_icon3' => 'cms',
        'section5_main_img' => 'cms',
        'section5_sub_icon1' => 'cms',
        'section5_sub_icon2' => 'cms',
        'section5_sub_icon3' => 'cms',
        'section6_img1' => 'cms',
        'section6_img2' => 'cms',
        'section6_img3' => 'cms',
    ];

    public function __construct(
        protected CmsRepositoryInterface $repository,
        protected UploadService $uploadService,
    ) {
    }

    /**
     * Get all CMS records ordered by ID desc.
     */
    public function all()
    {
        return Cms::orderBy('id', 'desc')->get();
    }

    /**
     * Find a CMS record or return null.
     */
    public function find(int|string $id): ?Model
    {
        return $this->repository->find($id);
    }

    /**
     * Create a CMS record with image uploads.
     */
    public function store(Request $request, array $data): Model
    {
        $data = $this->processImageUploads($request, $data);

        return $this->repository->create($data);
    }

    /**
     * Update a CMS record with image uploads.
     */
    public function update(int|string $id, Request $request, array $data): bool
    {
        $existing = $this->repository->findOrFail($id);
        $data = $this->processImageUploads($request, $data, $existing);

        return $this->repository->update($id, $data);
    }

    /**
     * Delete a CMS record.
     */
    public function destroy(int|string $id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * Process all image uploads, replacing old files if updating.
     */
    protected function processImageUploads(Request $request, array $data, ?Model $existing = null): array
    {
        foreach ($this->imageFields as $field => $folder) {
            if ($request->hasFile($field)) {
                $oldFile = $existing?->{$field} ?? null;
                $data[$field] = $this->uploadService->upload(
                    $request->file($field),
                    $folder,
                    $oldFile,
                );
            }
        }

        return $data;
    }
}
