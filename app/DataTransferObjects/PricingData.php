<?php

namespace App\DataTransferObjects;

/**
 * DTO for pricing / rate group data.
 */
readonly class PricingData
{
    public function __construct(
        public string $startDate,
        public string $endDate,
        public string $typeOfPrice = 'default',
        public string $nameOfPrice = '',
        public ?float $price = null,
        public ?float $mondayPrice = null,
        public ?float $tuesdayPrice = null,
        public ?float $wednesdayPrice = null,
        public ?float $thrusdayPrice = null,
        public ?float $fridayPrice = null,
        public ?float $saturdayPrice = null,
        public ?float $sundayPrice = null,
        public ?string $discountWeekly = null,
        public ?string $discountMonthly = null,
        public string $isAvailable = '1',
        public string $platformType = 'airbnb',
        public string $currency = 'USD',
        public ?float $basePrice = null,
        public ?string $notes = null,
        public ?string $minStay = null,
        public ?string $baseMinStay = null,
        public ?string $checkinDay = null,
        public ?string $checkoutDay = null,
    ) {
    }

    public static function fromRequest(\Illuminate\Http\Request $request): self
    {
        return new self(
            startDate: $request->input('start_date', ''),
            endDate: $request->input('end_date', ''),
            typeOfPrice: $request->input('type_of_price', 'default'),
            nameOfPrice: $request->input('name_of_price', ''),
            price: $request->input('price'),
            mondayPrice: $request->input('monday_price'),
            tuesdayPrice: $request->input('tuesday_price'),
            wednesdayPrice: $request->input('wednesday_price'),
            thrusdayPrice: $request->input('thrusday_price'),
            fridayPrice: $request->input('friday_price'),
            saturdayPrice: $request->input('saturday_price'),
            sundayPrice: $request->input('sunday_price'),
            discountWeekly: $request->input('discount_weekly'),
            discountMonthly: $request->input('discount_monthly'),
            isAvailable: $request->input('is_available', '1'),
            platformType: $request->input('platform_type', 'airbnb'),
            currency: $request->input('currency', 'USD'),
            basePrice: $request->input('base_price'),
            notes: $request->input('notes'),
            minStay: $request->input('min_stay'),
            baseMinStay: $request->input('base_min_stay'),
            checkinDay: $request->input('checkin_day'),
            checkoutDay: $request->input('checkout_day'),
        );
    }
}
