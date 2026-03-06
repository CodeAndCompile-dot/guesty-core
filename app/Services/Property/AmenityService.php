<?php

namespace App\Services\Property;

use App\Models\PropertyAmenity;
use App\Models\PropertyAmenityGroup;
use App\Services\Media\UploadService;
use App\Support\Traits\HasImageUpload;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AmenityService
{
    use HasImageUpload;

    public function __construct(
        protected UploadService $uploadService,
    ) {
    }

    /* ------------------------------------------------------------------ */
    /*  Amenity Groups                                                      */
    /* ------------------------------------------------------------------ */

    public function getGroupsByProperty(int|string $propertyId): Collection
    {
        return PropertyAmenityGroup::where('property_id', $propertyId)->orderBy('id', 'desc')->get();
    }

    public function findGroup(int|string $id): ?PropertyAmenityGroup
    {
        return PropertyAmenityGroup::find($id);
    }

    public function findGroupOrFail(int|string $id): PropertyAmenityGroup
    {
        return PropertyAmenityGroup::findOrFail($id);
    }

    public function storeGroup(int|string $propertyId, Request $request, array $data): PropertyAmenityGroup
    {
        $data['property_id'] = $propertyId;

        $imageFields = ['image' => 'properties-group-amenities'];
        $data = $this->processImageUploads($request, $data, $imageFields);

        return PropertyAmenityGroup::create($data);
    }

    public function updateGroup(int|string $id, Request $request, array $data): bool
    {
        $group = PropertyAmenityGroup::findOrFail($id);

        $imageFields = ['image' => 'properties-group-amenities'];
        $existingImages = ['image' => $group->image];
        $data = $this->processImageUploads($request, $data, $imageFields, $existingImages);

        return $group->update($data);
    }

    public function destroyGroup(int|string $id): bool
    {
        // Cascade: delete all amenities in this group
        PropertyAmenity::where('property_amenity_id', $id)->delete();

        return PropertyAmenityGroup::findOrFail($id)->delete();
    }

    public function activateGroup(int|string $id): bool
    {
        return PropertyAmenityGroup::findOrFail($id)->update(['status' => 'true']);
    }

    public function deactivateGroup(int|string $id): bool
    {
        return PropertyAmenityGroup::findOrFail($id)->update(['status' => 'false']);
    }

    /* ------------------------------------------------------------------ */
    /*  Amenities (within a Group)                                          */
    /* ------------------------------------------------------------------ */

    public function getAmenitiesByGroup(int|string $groupId): Collection
    {
        return PropertyAmenity::where('property_amenity_id', $groupId)->orderBy('sorting')->get();
    }

    public function findAmenity(int|string $id): ?PropertyAmenity
    {
        return PropertyAmenity::find($id);
    }

    public function findAmenityOrFail(int|string $id): PropertyAmenity
    {
        return PropertyAmenity::findOrFail($id);
    }

    public function storeAmenity(int|string $groupId, Request $request, array $data): PropertyAmenity
    {
        $data['property_amenity_id'] = $groupId;

        $imageFields = ['image' => 'properties-amenities'];
        $data = $this->processImageUploads($request, $data, $imageFields);

        return PropertyAmenity::create($data);
    }

    public function updateAmenity(int|string $id, Request $request, array $data): bool
    {
        $amenity = PropertyAmenity::findOrFail($id);

        $imageFields = ['image' => 'properties-amenities'];
        $existingImages = ['image' => $amenity->image];
        $data = $this->processImageUploads($request, $data, $imageFields, $existingImages);

        return $amenity->update($data);
    }

    public function destroyAmenity(int|string $id): bool
    {
        return PropertyAmenity::findOrFail($id)->delete();
    }

    public function activateAmenity(int|string $id): bool
    {
        return PropertyAmenity::findOrFail($id)->update(['status' => 'true']);
    }

    public function deactivateAmenity(int|string $id): bool
    {
        return PropertyAmenity::findOrFail($id)->update(['status' => 'false']);
    }

    /**
     * Duplicate an amenity.
     */
    public function duplicateAmenity(int|string $id): PropertyAmenity
    {
        $original = PropertyAmenity::findOrFail($id);
        $replica = $original->replicate();
        $replica->save();

        return $replica;
    }
}
