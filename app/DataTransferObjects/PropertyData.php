<?php

namespace App\DataTransferObjects;

/**
 * DTO for property data transfer between layers.
 */
readonly class PropertyData
{
    public function __construct(
        public string $name,
        public string $seoUrl,
        public ?string $heading = null,
        public ?string $price = '0',
        public ?string $address = null,
        public ?int $locationId = null,
        public ?string $propertyStatus = null,
        public ?string $mobile = null,
        public ?string $email = null,
        public ?string $website = null,
        public ?string $shortDescription = null,
        public ?string $longDescription = null,
        public ?string $description = null,
        public ?string $cancellationPolicy = null,
        public ?string $bookingPolicy = null,
        public ?int $bedroom = 0,
        public ?int $bathroom = null,
        public ?int $beds = null,
        public ?int $sleeps = null,
        public ?string $area = null,
        public ?string $category = null,
        public ?string $bedType = null,
        public ?string $propertyView = null,
        public string $status = 'true',
        public ?int $minStay = null,
        public ?int $standardRate = null,
    ) {
    }

    public static function fromRequest(\Illuminate\Http\Request $request): self
    {
        return new self(
            name: $request->input('name'),
            seoUrl: $request->input('seo_url'),
            heading: $request->input('heading'),
            price: $request->input('price', '0'),
            address: $request->input('address'),
            locationId: $request->input('location_id'),
            propertyStatus: $request->input('property_status'),
            mobile: $request->input('mobile'),
            email: $request->input('email'),
            website: $request->input('website'),
            shortDescription: $request->input('short_description'),
            longDescription: $request->input('long_description'),
            description: $request->input('description'),
            cancellationPolicy: $request->input('cancellation_policy'),
            bookingPolicy: $request->input('booking_policy'),
            bedroom: $request->input('bedroom', 0),
            bathroom: $request->input('bathroom'),
            beds: $request->input('beds'),
            sleeps: $request->input('sleeps'),
            area: $request->input('area'),
            category: $request->input('category'),
            bedType: $request->input('bed_type'),
            propertyView: $request->input('property_view'),
            status: $request->input('status', 'true'),
            minStay: $request->input('min_stay'),
            standardRate: $request->input('standard_rate'),
        );
    }
}
