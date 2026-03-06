<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface PropertyRateGroupRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get all rate groups for a given property.
     */
    public function getByProperty(int|string $propertyId): Collection;

    /**
     * Check for overlapping date ranges (excluding a given record).
     */
    public function findOverlapping(
        int|string $propertyId,
        string $startDate,
        string $endDate,
        ?int $excludeId = null,
    ): Collection;
}
