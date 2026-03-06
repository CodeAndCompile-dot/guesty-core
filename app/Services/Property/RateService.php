<?php

namespace App\Services\Property;

use App\Models\PropertyRate;
use App\Repositories\Contracts\PropertyRateGroupRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RateService
{
    public function __construct(
        protected PropertyRateGroupRepositoryInterface $rateGroupRepo,
    ) {
    }

    /**
     * Get all rate groups for a property.
     */
    public function getByProperty(int|string $propertyId): Collection
    {
        return $this->rateGroupRepo->getByProperty($propertyId);
    }

    public function find(int|string $id): ?Model
    {
        return $this->rateGroupRepo->find($id);
    }

    public function findOrFail(int|string $id): Model
    {
        return $this->rateGroupRepo->findOrFail($id);
    }

    /**
     * Store a rate group and generate per-day PropertyRate records.
     */
    public function store(int|string $propertyId, array $data): Model
    {
        return DB::transaction(function () use ($propertyId, $data) {
            $data['property_id'] = $propertyId;
            $data['start_date_timestamp'] = strtotime($data['start_date'] ?? '');
            $data['end_date_timestamp']   = strtotime($data['end_date'] ?? '');

            // Null out irrelevant price fields depending on type
            if (($data['type_of_price'] ?? 'default') === 'default') {
                foreach (['monday_price', 'tuesday_price', 'wednesday_price', 'thrusday_price', 'friday_price', 'saturday_price', 'sunday_price'] as $dayField) {
                    $data[$dayField] = null;
                }
            } else {
                $data['price'] = null;
            }

            $group = $this->rateGroupRepo->create($data);

            $this->generateDailyRates($group, $data);

            return $group;
        });
    }

    /**
     * Update a rate group and regenerate per-day PropertyRate records.
     */
    public function update(int|string $id, int|string $propertyId, array $data): bool
    {
        return DB::transaction(function () use ($id, $propertyId, $data) {
            $data['start_date_timestamp'] = strtotime($data['start_date'] ?? '');
            $data['end_date_timestamp']   = strtotime($data['end_date'] ?? '');

            if (($data['type_of_price'] ?? 'default') === 'default') {
                foreach (['monday_price', 'tuesday_price', 'wednesday_price', 'thrusday_price', 'friday_price', 'saturday_price', 'sunday_price'] as $dayField) {
                    $data[$dayField] = null;
                }
            } else {
                $data['price'] = null;
            }

            $result = $this->rateGroupRepo->update($id, $data);

            // Regenerate daily rates
            $group = $this->rateGroupRepo->findOrFail($id);
            $this->generateDailyRates($group, $data);

            return $result;
        });
    }

    /**
     * Delete a rate group and its per-day rates.
     */
    public function destroy(int|string $id): bool
    {
        return DB::transaction(function () use ($id) {
            PropertyRate::where('rate_group_id', $id)->delete();

            return $this->rateGroupRepo->delete($id);
        });
    }

    /**
     * Duplicate a rate group.
     */
    public function duplicate(int|string $id): Model
    {
        $original = $this->rateGroupRepo->findOrFail($id);
        $replica = $original->replicate();
        $replica->save();

        return $replica;
    }

    /**
     * Check for overlapping date ranges for a property.
     */
    public function hasOverlap(int|string $propertyId, string $startDate, string $endDate, ?int $excludeId = null): bool
    {
        return $this->rateGroupRepo->findOverlapping($propertyId, $startDate, $endDate, $excludeId)->isNotEmpty();
    }

    /* ------------------------------------------------------------------ */
    /*  Private helpers                                                     */
    /* ------------------------------------------------------------------ */

    /**
     * Generate per-day PropertyRate records from a rate group.
     * Mirrors legacy ModelHelper::saveSIngleDatePropertyRate().
     */
    private function generateDailyRates(Model $group, array $data): void
    {
        // Delete existing daily rates for this group
        PropertyRate::where('rate_group_id', $group->id)->delete();

        if (empty($data['start_date']) || empty($data['end_date'])) {
            return;
        }

        $startTimestamp = strtotime($data['start_date']);
        $endTimestamp   = strtotime($data['end_date']);
        $dayCount       = (int) ceil(($endTimestamp - $startTimestamp) / 86400);

        for ($i = 0; $i <= $dayCount; $i++) {
            $date = date('Y-m-d', strtotime("+{$i} day", $startTimestamp));

            $rateData = [
                'property_id'          => $group->property_id,
                'rate_group_id'        => $group->id,
                'single_date'          => $date,
                'single_date_timestamp' => strtotime($date),
                'discount_weekly'      => $data['discount_weekly'] ?? null,
                'discount_monthly'     => $data['discount_monthly'] ?? null,
                'is_available'         => $data['is_available'] ?? '1',
                'platform_type'        => $data['platform_type'] ?? 'airbnb',
                'currency'             => $data['currency'] ?? 'USD',
                'base_price'           => $data['base_price'] ?? null,
                'notes'                => $data['notes'] ?? null,
                'min_stay'             => $data['min_stay'] ?? null,
                'base_min_stay'        => $data['base_min_stay'] ?? null,
                'checkin_day'          => $data['checkin_day'] ?? null,
                'checkout_day'         => $data['checkout_day'] ?? null,
            ];

            // Determine price based on pricing type
            if (($data['type_of_price'] ?? 'default') === 'default') {
                $rateData['price'] = $data['price'] ?? 0;
            } else {
                $dayName = date('l', strtotime($date));
                $rateData['price'] = match ($dayName) {
                    'Monday'    => $data['monday_price'] ?? 0,
                    'Tuesday'   => $data['tuesday_price'] ?? 0,
                    'Wednesday' => $data['wednesday_price'] ?? 0,
                    'Thursday'  => $data['thrusday_price'] ?? 0,
                    'Friday'    => $data['friday_price'] ?? 0,
                    'Saturday'  => $data['saturday_price'] ?? 0,
                    'Sunday'    => $data['sunday_price'] ?? 0,
                    default     => 0,
                };
            }

            PropertyRate::create($rateData);
        }
    }
}
