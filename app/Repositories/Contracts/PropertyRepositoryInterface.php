<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface PropertyRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get all properties ordered by id descending.
     */
    public function allDescending(): Collection;

    /**
     * Find a property by SEO URL.
     */
    public function findBySeoUrl(string $seoUrl): ?\Illuminate\Database\Eloquent\Model;

    /**
     * Duplicate a property and its related galleries & amenity groups.
     */
    public function duplicateWithRelations(int|string $id): \Illuminate\Database\Eloquent\Model;
}
