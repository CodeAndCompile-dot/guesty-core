<?php

namespace App\Helpers;

use App\Models\BasicSetting;
use App\Models\BlogCategory;
use App\Models\AttractionCategory;
use App\Models\Cms;
use App\Models\Location;
use App\Models\Property;
use App\Models\PropertyGallery;
use Illuminate\Database\Eloquent\Collection;

class ModelHelper
{
    /**
     * Get a value from the basic_settings table by name.
     */
    public function getDataFromSetting(string $name): ?string
    {
        $data = BasicSetting::where('name', $name)->first();

        return $data?->value;
    }

    /**
     * Get a key=>value list of all locations (id => name).
     */
    public function getLocationSelectList(): array
    {
        return Location::pluck('name', 'id')->toArray();
    }

    /**
     * Get parent-level locations (where is_parent is null).
     */
    public function getParentLocationSelectList(): array
    {
        return Location::whereNull('is_parent')->pluck('name', 'id')->toArray();
    }

    /**
     * Get a key=>value list of all properties (id => name).
     * Note: legacy typo "Propperty" preserved for view compatibility.
     */
    public function getProppertySelectList(): array
    {
        return Property::pluck('name', 'id')->toArray();
    }

    /**
     * Alternate spelling used by some legacy views.
     */
    public function getProperptySelectList(): array
    {
        return $this->getProppertySelectList();
    }

    /**
     * Get gallery images for a property, ordered by sorting.
     */
    public function getImageByProduct(int|string $productId): Collection
    {
        return PropertyGallery::where('property_id', $productId)->orderBy('sorting', 'asc')->get();
    }

    /**
     * Get CMS pages as a select list (id => name).
     */
    public function getPageSelectList(): array
    {
        return Cms::pluck('name', 'id')->toArray();
    }

    /**
     * Stub: attribute group products (Phase 4+).
     */
    public function getAttributeGroupProduct(int|string $productId): array
    {
        return [];
    }

    /**
     * Stub: attribute group select list (Phase 4+).
     */
    public function getAttributeGroupSelect(): array
    {
        return [];
    }

    /**
     * Get blog categories as a select list (id => title).
     */
    public function getBlogCategoriesSelect(): array
    {
        return BlogCategory::pluck('title', 'id')->toArray();
    }

    /**
     * Get attraction categories as a select list (id => name).
     */
    public function getAttractionCategorySelect(): array
    {
        return AttractionCategory::pluck('name', 'id')->toArray();
    }

    /* ------------------------------------------------------------------ */
    /*  Display helpers (legacy parity)                                    */
    /* ------------------------------------------------------------------ */

    /**
     * Return CSS display style for pet fee visibility.
     *
     * Legacy: ModelHelper::showPetFee()
     */
    public function showPetFee($pet_fee): string
    {
        if ($pet_fee && $pet_fee > 0) {
            return 'display:block;';
        }

        return 'display:none;';
    }

    /**
     * Return CSS display style for pool fee visibility.
     *
     * Legacy: ModelHelper::showpoolFee()
     */
    public function showpoolFee($pet_fee): string
    {
        if ($pet_fee && $pet_fee > 0) {
            return 'display:block;';
        }

        return 'display:none;';
    }
}
