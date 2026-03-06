<?php

namespace App\Services\Property;

use App\Models\PropertyAmenity;
use App\Models\PropertyAmenityGroup;
use App\Models\PropertyFee;
use App\Models\PropertyGallery;
use App\Models\PropertyRate;
use App\Models\PropertyRateGroup;
use App\Models\PropertyRoom;
use App\Models\PropertySpace;
use App\Repositories\Contracts\PropertyRepositoryInterface;
use App\Services\Media\UploadService;
use App\Support\Traits\HasActivation;
use App\Support\Traits\HasImageUpload;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertyService
{
    use HasActivation;
    use HasImageUpload;

    public function __construct(
        protected PropertyRepositoryInterface $repository,
        protected UploadService $uploadService,
    ) {
    }

    public function all(): Collection
    {
        return $this->repository->allDescending();
    }

    public function find(int|string $id): ?Model
    {
        return $this->repository->find($id);
    }

    public function findOrFail(int|string $id): Model
    {
        return $this->repository->findOrFail($id);
    }

    /**
     * Store a new property with galleries, fees, and spaces — all inside a transaction.
     */
    public function store(Request $request, array $data): Model
    {
        return DB::transaction(function () use ($request, $data) {
            // Upload images
            $imageFields = [
                'banner_image'   => 'properties',
                'feature_image'  => 'properties',
                'welcome_package_attachment'     => 'properties',
                'rental_aggrement_attachment'     => 'properties',
            ];
            $data = $this->processImageUploads($request, $data, $imageFields);

            $property = $this->repository->create($data);

            // Bulk gallery images
            $this->syncGalleryImages($request, $property);

            // Fees
            $this->syncFees($request, $property);

            // Spaces
            $this->syncSpaces($request, $property);

            return $property;
        });
    }

    /**
     * Update a property with galleries, fees, and spaces — all inside a transaction.
     */
    public function update(int|string $id, Request $request, array $data): bool
    {
        return DB::transaction(function () use ($id, $request, $data) {
            $existing = $this->repository->findOrFail($id);

            // Upload images (replace old ones)
            $imageFields = [
                'banner_image'   => 'properties',
                'feature_image'  => 'properties',
                'welcome_package_attachment'     => 'properties',
                'rental_aggrement_attachment'     => 'properties',
            ];
            $existingImages = [];
            foreach (array_keys($imageFields) as $field) {
                $existingImages[$field] = $existing->{$field} ?? null;
            }
            $data = $this->processImageUploads($request, $data, $imageFields, $existingImages);

            $result = $this->repository->update($id, $data);

            // Update gallery (delete removed, add new)
            $this->syncGalleryImagesOnUpdate($request, $existing);

            // Spaces
            $this->syncSpacesOnUpdate($request, $existing);

            return $result;
        });
    }

    /**
     * Delete a property and cascade to all child records.
     */
    public function destroy(int|string $id): bool
    {
        return DB::transaction(function () use ($id) {
            $property = $this->repository->findOrFail($id);

            // Cascade delete all related data
            PropertyGallery::where('property_id', $id)->delete();
            PropertyFee::where('property_id', $id)->delete();
            PropertySpace::where('property_id', $id)->delete();
            PropertyRate::where('property_id', $id)->delete();
            PropertyRateGroup::where('property_id', $id)->delete();
            PropertyRoom::where('property_id', $id)->delete();

            // Delete amenity groups and their amenities
            $groups = PropertyAmenityGroup::where('property_id', $id)->get();
            foreach ($groups as $group) {
                PropertyAmenity::where('property_amenity_id', $group->id)->delete();
            }
            PropertyAmenityGroup::where('property_id', $id)->delete();

            return $this->repository->delete($id);
        });
    }

    /**
     * Duplicate a property with galleries and amenity groups.
     */
    public function duplicate(int|string $id): Model
    {
        return $this->repository->duplicateWithRelations($id);
    }

    public function activate(int|string $id): bool
    {
        return $this->activateRecord($this->repository, $id);
    }

    public function deactivate(int|string $id): bool
    {
        return $this->deactivateRecord($this->repository, $id);
    }

    /**
     * Update gallery caption and sorting (AJAX).
     */
    public function updateCaptionSort(array $ids, array $sortings, array $captions): void
    {
        foreach ($ids as $index => $galleryId) {
            PropertyGallery::where('id', $galleryId)->update([
                'sorting' => $sortings[$index] ?? 0,
                'caption' => $captions[$index] ?? null,
            ]);
        }
    }

    /**
     * Delete a single gallery image (AJAX).
     */
    public function deleteGalleryImage(int|string $galleryId): bool
    {
        $gallery = PropertyGallery::findOrFail($galleryId);
        if ($gallery->image) {
            $this->uploadService->delete($gallery->image);
        }

        return $gallery->delete();
    }

    /**
     * Delete a single property space (AJAX).
     */
    public function deleteSpace(int|string $spaceId): bool
    {
        return PropertySpace::findOrFail($spaceId)->delete();
    }

    /* ------------------------------------------------------------------ */
    /*  Private helpers                                                     */
    /* ------------------------------------------------------------------ */

    private function syncGalleryImages(Request $request, Model $property): void
    {
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $this->uploadService->upload($image, 'properties');
                PropertyGallery::create([
                    'property_id' => $property->id,
                    'image'       => $path,
                ]);
            }
        }
    }

    private function syncGalleryImagesOnUpdate(Request $request, Model $property): void
    {
        // Delete galleries not in preloaded list (scoped to property)
        if ($request->has('preloaded')) {
            PropertyGallery::where('property_id', $property->id)
                ->whereNotIn('id', $request->input('preloaded', []))
                ->delete();
        }

        // Add new images
        $this->syncGalleryImages($request, $property);
    }

    private function syncFees(Request $request, Model $property): void
    {
        // Delete existing and recreate
        PropertyFee::where('property_id', $property->id)->delete();

        if ($request->has('fee_name')) {
            $feeNames  = $request->input('fee_name', []);
            $feeRates  = $request->input('fee_rate', []);
            $feeTypes  = $request->input('fee_type', []);
            $feeApplys = $request->input('fee_apply', []);

            foreach ($feeNames as $i => $name) {
                if (! empty($name)) {
                    PropertyFee::create([
                        'property_id' => $property->id,
                        'fee_name'    => $name,
                        'fee_rate'    => $feeRates[$i] ?? '',
                        'fee_type'    => $feeTypes[$i] ?? 'Excat',
                        'fee_apply'   => $feeApplys[$i] ?? 'total',
                    ]);
                }
            }
        }
    }

    private function syncSpaces(Request $request, Model $property): void
    {
        if ($request->has('space_name')) {
            $names = $request->input('space_name', []);
            foreach ($names as $i => $name) {
                if (! empty($name)) {
                    $spaceData = [
                        'property_id' => $property->id,
                        'space_name'  => $name,
                    ];
                    // Handle per-space image uploads
                    if ($request->hasFile("space_image.$i")) {
                        $spaceData['space_image'] = $this->uploadService->upload(
                            $request->file("space_image.$i"),
                            'properties'
                        );
                    }
                    PropertySpace::create($spaceData);
                }
            }
        }
    }

    private function syncSpacesOnUpdate(Request $request, Model $property): void
    {
        if ($request->has('space_name')) {
            $names   = $request->input('space_name', []);
            $spaceIds = $request->input('space_id', []);

            foreach ($names as $i => $name) {
                if (empty($name)) {
                    continue;
                }
                $spaceData = ['space_name' => $name];
                if ($request->hasFile("space_image.$i")) {
                    $spaceData['space_image'] = $this->uploadService->upload(
                        $request->file("space_image.$i"),
                        'properties'
                    );
                }

                if (! empty($spaceIds[$i])) {
                    PropertySpace::where('id', $spaceIds[$i])->update($spaceData);
                } else {
                    $spaceData['property_id'] = $property->id;
                    PropertySpace::create($spaceData);
                }
            }
        }
    }
}
