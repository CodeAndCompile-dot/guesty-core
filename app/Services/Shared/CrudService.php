<?php

namespace App\Services\Shared;

use App\Repositories\Contracts\BaseRepositoryInterface;
use App\Services\Media\UploadService;
use App\Support\Traits\HasActivation;
use App\Support\Traits\HasDuplication;
use App\Support\Traits\HasImageUpload;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CrudService
{
    use HasActivation;
    use HasDuplication;
    use HasImageUpload;

    public function __construct(
        protected BaseRepositoryInterface $repository,
        protected UploadService $uploadService,
    ) {
    }

    /**
     * Get all records.
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->repository->all($columns);
    }

    /**
     * Get paginated records.
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $columns);
    }

    /**
     * Find a record by ID.
     */
    public function find(int|string $id): ?Model
    {
        return $this->repository->find($id);
    }

    /**
     * Find a record by ID or throw.
     */
    public function findOrFail(int|string $id): Model
    {
        return $this->repository->findOrFail($id);
    }

    /**
     * Create a new record, processing any image uploads.
     *
     * @param  Request  $request  The incoming request
     * @param  array  $data  Validated data
     * @param  array<string, string>  $imageFields  Map of field name => upload folder
     */
    public function store(Request $request, array $data, array $imageFields = []): Model
    {
        if (! empty($imageFields)) {
            $data = $this->processImageUploads($request, $data, $imageFields);
        }

        return $this->repository->create($data);
    }

    /**
     * Update an existing record, processing any image uploads.
     *
     * @param  int|string  $id  Record ID
     * @param  Request  $request  The incoming request
     * @param  array  $data  Validated data
     * @param  array<string, string>  $imageFields  Map of field name => upload folder
     */
    public function update(int|string $id, Request $request, array $data, array $imageFields = []): bool
    {
        if (! empty($imageFields)) {
            $existing = $this->repository->findOrFail($id);

            $existingImages = [];
            foreach (array_keys($imageFields) as $field) {
                $existingImages[$field] = $existing->{$field} ?? null;
            }

            $data = $this->processImageUploads($request, $data, $imageFields, $existingImages);
        }

        return $this->repository->update($id, $data);
    }

    /**
     * Delete a record and its associated image files.
     *
     * @param  int|string  $id  Record ID
     * @param  array<string>  $imageFields  List of field names that contain file paths
     */
    public function destroy(int|string $id, array $imageFields = []): bool
    {
        if (! empty($imageFields)) {
            $record = $this->repository->findOrFail($id);

            foreach ($imageFields as $field) {
                if (! empty($record->{$field})) {
                    $this->uploadService->delete($record->{$field});
                }
            }
        }

        return $this->repository->delete($id);
    }

    /**
     * Activate a record.
     */
    public function activate(int|string $id): bool
    {
        return $this->activateRecord($this->repository, $id);
    }

    /**
     * Deactivate a record.
     */
    public function deactivate(int|string $id): bool
    {
        return $this->deactivateRecord($this->repository, $id);
    }

    /**
     * Duplicate a record.
     */
    public function duplicate(int|string $id): Model
    {
        return $this->duplicateRecord($this->repository, $id);
    }
}
