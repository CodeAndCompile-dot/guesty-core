<?php

namespace App\DataTransferObjects;

/**
 * DTO for rate calendar / daily rate display.
 */
readonly class RateCalendarData
{
    public function __construct(
        public string $date,
        public float $price,
        public string $isAvailable = '1',
        public string $platformType = 'airbnb',
        public string $currency = 'USD',
        public ?string $minStay = null,
        public ?int $rateGroupId = null,
    ) {
    }

    /**
     * Build from a PropertyRate model.
     */
    public static function fromModel(\App\Models\PropertyRate $rate): self
    {
        return new self(
            date: $rate->single_date?->format('Y-m-d') ?? '',
            price: (float) $rate->price,
            isAvailable: $rate->is_available ?? '1',
            platformType: $rate->platform_type ?? 'airbnb',
            currency: $rate->currency ?? 'USD',
            minStay: $rate->min_stay,
            rateGroupId: $rate->rate_group_id,
        );
    }
}
