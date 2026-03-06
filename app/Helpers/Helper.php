<?php

namespace App\Helpers;

class Helper
{
    /**
     * Boolean dropdown values (string keys for Blade forms).
     */
    public function getBooleanDataActual(): array
    {
        return ['false' => 'false', 'true' => 'true'];
    }

    public function getBooleanData(): array
    {
        return ['0' => 'false', '1' => 'true'];
    }

    public function getfirstTrueBooleanData(): array
    {
        return ['1' => 'true', '0' => 'false'];
    }

    /**
     * Week day names for rate forms.
     */
    public function getWeekNameSelect(): array
    {
        return ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    }

    /**
     * Property status options.
     */
    public function getPropertyStatus(): array
    {
        return [
            'Available'    => 'Available',
            'No Available' => 'No Available',
            'Rented'       => 'Rented',
            'Trending'     => 'Trending',
            'Sale'         => 'Sale',
        ];
    }

    /**
     * CMS template options.
     */
    public function getTempletes(): array
    {
        return [
            'home'                => 'Home',
            'onboarding'          => 'onboarding',
            'about'               => 'about',
            'prearrival'          => 'prearrival',
            'about-owner'         => 'about-owner',
            'property-detail'     => 'property-detail',
            'common'              => 'Common',
            'contact'             => 'contact',
            'blogs'               => 'blogs',
            'map'                 => 'map',
            'reviews'             => 'reviews',
            'gallery'             => 'gallery',
            'property-management' => 'property-management',
            'property-list'       => 'property-list',
            'attractions'         => 'attractions',
            'get-quote'           => 'get-quote',
            'faq'                 => 'FAQ',
            'test'                => 'test',
            'services'            => 'services',
            'partner'             => 'partner',
            'privacy'             => 'privacy',
        ];
    }

    /**
     * Town list for property management requests.
     */
    public function getTownList(): array
    {
        return [
            'bentonville'    => 'bentonville',
            'rogers'         => 'rogers',
            'bella vista'    => 'bella vista',
            'fayetteville'   => 'fayetteville',
            'springdale'     => 'springdale',
            'eureka springs' => 'eureka springs',
            'beaver lake'    => 'beaver lake',
            'siloam springs' => 'siloam springs',
        ];
    }

    /**
     * Coupon type list.
     */
    public function getCoupanCodeList(): array
    {
        return ['exact' => 'Exact', 'percentage' => 'Percentage'];
    }

    /**
     * Field type options for dynamic forms.
     */
    public function getTypeOfField(): array
    {
        return [
            'select'   => 'select',
            'text'     => 'text',
            'color'    => 'color',
            'date'     => 'date',
            'time'     => 'time',
            'number'   => 'number',
            'textarea' => 'textarea',
        ];
    }

    /**
     * Generate SEO-friendly URL slug.
     */
    public function getSeoUrlGet(string $title): string
    {
        return strtolower(str_replace(
            ['/', '\\', "'", '"', ',', ';', '<', '>', '&', ' ', '*', '!', '@', '#', '$', '%', '+', ',', '.', '`', '~', ':', '[', ']', '{', '}', '(', ')', '?'],
            '-',
            $title
        ));
    }

    /**
     * Return an image path, or a fallback if the file doesn't exist.
     */
    public function getImage(string $image): string
    {
        if ($image !== '' && is_file(public_path($image))) {
            return $image;
        }

        return 'uploads/no-image.jpg';
    }
}
