<?php

namespace App\Repositories\Eloquent;

use App\Models\Property;
use App\Models\PropertyAmenity;
use App\Models\PropertyAmenityGroup;
use App\Models\PropertyGallery;
use App\Repositories\Contracts\PropertyRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PropertyRepository extends BaseRepository implements PropertyRepositoryInterface
{
    public function __construct(Property $model)
    {
        parent::__construct($model);
    }

    public function allDescending(): Collection
    {
        return $this->model->newQuery()->orderBy('id', 'desc')->get();
    }

    public function findBySeoUrl(string $seoUrl): ?Model
    {
        return $this->model->newQuery()->where('seo_url', $seoUrl)->first();
    }

    /**
     * Duplicate a property and its related galleries & amenity groups (with nested amenities).
     */
    public function duplicateWithRelations(int|string $id): Model
    {
        $original = $this->findOrFail($id);
        $replica = $original->replicate();
        $replica->seo_url = $original->seo_url . '-' . Str::random(5);
        $replica->save();

        // Copy galleries
        foreach ($original->galleries as $gallery) {
            $newGallery = $gallery->replicate();
            $newGallery->property_id = $replica->id;
            $newGallery->save();
        }

        // Copy amenity groups with nested amenities
        foreach ($original->amenityGroups as $group) {
            $newGroup = $group->replicate();
            $newGroup->property_id = $replica->id;
            $newGroup->save();

            foreach ($group->amenities as $amenity) {
                $newAmenity = $amenity->replicate();
                $newAmenity->property_amenity_id = $newGroup->id;
                $newAmenity->save();
            }
        }

        return $replica;
    }
}
