<?php

namespace App\Repositories\Eloquent;

use App\Models\PropertyRateGroup;
use App\Repositories\Contracts\PropertyRateGroupRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PropertyRateGroupRepository extends BaseRepository implements PropertyRateGroupRepositoryInterface
{
    public function __construct(PropertyRateGroup $model)
    {
        parent::__construct($model);
    }

    public function getByProperty(int|string $propertyId): Collection
    {
        return $this->model->newQuery()
            ->where('property_id', $propertyId)
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * Find rate groups that overlap with a given date range for a property.
     * Uses proper range overlap: existing.start <= new.end AND existing.end >= new.start
     */
    public function findOverlapping(
        int|string $propertyId,
        string $startDate,
        string $endDate,
        ?int $excludeId = null,
    ): Collection {
        $query = $this->model->newQuery()
            ->where('property_id', $propertyId)
            ->where('start_date', '<=', $endDate)
            ->where('end_date', '>=', $startDate);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->get();
    }
}
