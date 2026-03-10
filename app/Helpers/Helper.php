<?php

namespace App\Helpers;

use App\Models\GuestyAvailabilityPrice;
use App\Models\Property;
use App\Models\PropertyRate;
use Illuminate\Support\Facades\Session;

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

    /* ------------------------------------------------------------------ */
    /*  Phase 7: Booking status helpers (used by booking-enquiries views)  */
    /* ------------------------------------------------------------------ */

    /**
     * Render a booking-status badge/button for admin booking list.
     *
     * Legacy: uses url('admin/...') — we use route() for prefix-agnosticism.
     */
    public function getBookingStatus(?string $item, int $id): string
    {
        $s = '';

        if ($item === 'booked') {
            $s = '<a href="'.route('booking-enquiry-confirm', $id).'" class="btn btn-xs btn-primary">Accept Booking</a>';
        }
        if ($item === 'rental-aggrement-success' || $item === 'rental-aggrement') {
            $s = '<a href="'.route('booking-enquiry-confirm', $id).'" class="btn btn-xs btn-warning">Booking Accepted</a>';
        }
        if ($item === 'booking-confirmed') {
            $s = '<a href="javascript:;" class="btn btn-xs btn-success">Booking Confirmed</a>';
        }
        if ($item === 'booking-cancel') {
            $s = '<a href="javascript:;" class="btn btn-xs btn-danger">Booking Cancelled</a>';
        }

        return $s;
    }

    /**
     * Render a check/cross icon for boolean-ish 'true'/'false' string values.
     */
    public function checkStatus(?string $item): string
    {
        if ($item === 'true') {
            return '<i class="fa fa-check"></i>';
        }

        return '<i class="fa fa-times"></i>';
    }

    /* ------------------------------------------------------------------ */
    /*  Gross amount / rate calculations (legacy parity)                   */
    /* ------------------------------------------------------------------ */

    /**
     * Check Guesty availability & min-night constraint for a property.
     *
     * Legacy: Helper::getGrossDataCheckerDays()
     */
    public function getGrossDataCheckerDays($property, string $start_date, string $end_date): array
    {
        $now       = strtotime($start_date);
        $your_date = strtotime($end_date);
        $day       = (int) ceil(($your_date - $now) / 86400);

        if (! $property) {
            return ['status' => 400, 'message' => 'Not available'];
        }

        if ($day <= 0) {
            return ['status' => 400, 'message' => 'Not available those dates'];
        }

        $date         = date('Y-m-d', strtotime($start_date));
        $guesty_night = GuestyAvailabilityPrice::where([
            'listingId' => $property->_id,
            'status'    => 'available',
        ])->where('start_date', $date)->first();

        if (! $guesty_night) {
            return ['status' => 400, 'message' => 'Not available those dates'];
        }

        if ($guesty_night->minNights > $day) {
            return [
                'status'  => 400,
                'message' => 'The minimum stay requirement is '.$guesty_night->minNights.' nights for the selected dates',
            ];
        }

        return ['status' => 200, 'message' => 'Not available those dates'];
    }

    /**
     * Calculate gross rent for a non-Guesty property with day-by-day rate lookup,
     * checkin/checkout day validation, min-stay checks, and iCal overlap detection.
     *
     * Legacy: Helper::getGrossAmountData()
     */
    public function getGrossAmountData($property, string $start_date, string $end_date): array
    {
        $status       = false;
        $gross_amount = 0;
        $message      = '';
        $stay_flag    = 0;
        $day_gaurav   = $this->getWeekNameSelect();

        $now       = strtotime($start_date);
        $your_date = strtotime($end_date);
        $day       = (int) ceil(($your_date - $now) / 86400);
        $total_night = $day;

        if (! $property) {
            return ['status' => 'min-stay-day', 'gross_amount' => 0, 'total_night' => $total_night, 'message' => ''];
        }

        if ($day <= 0) {
            return ['status' => 'min-stay-day', 'gross_amount' => 0, 'total_night' => $total_night, 'message' => ''];
        }

        for ($i = 0; $i < $day; $i++) {
            $date = date('Y-m-d', strtotime("+{$i} day", strtotime($start_date)));
            $rate = PropertyRate::where(['property_id' => $property->id, 'single_date' => $date])->first();

            if ($rate) {
                $stay_flag = 1;

                if ($rate->min_stay > $day) {
                    $status = 'min-stay-day';
                    break;
                }

                // Checkin day validation (first night)
                if ($i === 0 && in_array($rate->checkin_day, ['0','1','2','3','4','5','6'])) {
                    $new_day = (int) date('w', strtotime($date));
                    if ($new_day != $rate->checkin_day) {
                        $status  = 'checkin-checkout-day';
                        $message = 'Please select checkin  day is '.$day_gaurav[$rate->checkin_day];
                        break;
                    }
                }

                // Checkout day validation (last night)
                if ($i === ($day - 1) && in_array($rate->checkout_day, ['0','1','2','3','4','5','6'])) {
                    $new_day = (int) date('w', strtotime('+1 day', strtotime($date)));
                    if ($new_day != $rate->checkout_day) {
                        $status  = 'checkin-checkout-day';
                        $message = 'Please select checkout  day is '.$day_gaurav[$rate->checkout_day];
                        break;
                    }
                }

                if ($rate->price) {
                    $gross_amount += $rate->price;
                    $status = true;
                }
            } else {
                // No specific rate — fall back to property standard_rate
                if ($property->standard_rate) {
                    if ($i === 0 && in_array($property->checkin_day, ['0','1','2','3','4','5','6'])) {
                        $new_day = (int) date('w', strtotime($date));
                        if ($new_day != $property->checkin_day) {
                            $status  = 'checkin-checkout-day';
                            $message = 'Please select checkin  day is '.$day_gaurav[$property->checkin_day];
                            break;
                        }
                    }

                    if ($i === ($day - 1) && in_array($property->checkout_day, ['0','1','2','3','4','5','6'])) {
                        $new_day = (int) date('w', strtotime('+1 day', strtotime($date)));
                        if ($new_day != $property->checkout_day) {
                            $status  = 'checkin-checkout-day';
                            $message = 'Please select checkout  day is '.$day_gaurav[$property->checkout_day];
                            break;
                        }
                    }

                    $gross_amount += $property->standard_rate;
                    $status = true;
                } else {
                    $status = 'date-price';
                    break;
                }
            }
        }

        // Min-stay check when no rate-level min_stay was specified
        if ($stay_flag === 0) {
            if (! $property->min_stay || $property->min_stay > $day) {
                $status = 'min-stay-day';
            }
        }

        // iCal overlap check
        $checkinCheckout = \LiveCart::iCalDataCheckInCheckOut($property->id);
        for ($i = 0; $i < $day; $i++) {
            $date = date('Y-m-d', strtotime("+{$i} day", strtotime($start_date)));
            if (in_array($date, $checkinCheckout['checkin'])) {
                $status = 'already-booked';
                break;
            }
        }

        return [
            'status'       => $status,
            'gross_amount' => $gross_amount,
            'total_night'  => $total_night,
            'message'      => $message,
        ];
    }

    /**
     * Calculate fee amount (flat or percentage) and build a display name.
     *
     * Legacy: Helper::getFeeAmountAndName()
     */
    public function getFeeAmountAndName($c, float $gross_amount): array
    {
        $name = $c->fee_name;

        if ($c->fee_type === 'Percentage') {
            $name  .= '('.$c->fee_rate.'%)';
            $amount = round(($gross_amount * $c->fee_rate) / 100, 2);
        } else {
            $amount = $c->fee_rate;
        }

        return ['status' => true, 'name' => $name, 'amount' => $amount];
    }

    /**
     * Build FullCalendar JSON events for a property's rates + iCal booked dates.
     *
     * Legacy: Helper::getPropertyRates()
     */
    public function getPropertyRates(int $id): string
    {
        $checkinCheckout  = \LiveCart::iCalDataCheckInCheckOut($id);
        $payment_currency = \ModelHelper::getDataFromSetting('payment_currency');
        $property         = Property::find($id);
        $price            = $property ? $property->standard_rate : '';

        $new_dates = [];

        for ($i = 0; $i <= 365; $i++) {
            $title       = $payment_currency.''.$price;
            $class       = 'available-date-full-calendar';
            $date_single = date('Y-m-d', strtotime("+ {$i}days", strtotime(date('Y-m-d'))));

            $a = PropertyRate::where(['property_id' => $id])
                ->where('single_date', $date_single)
                ->orderBy('id', 'desc')
                ->first();

            if ($a) {
                $title = $payment_currency.''.$a->price;
            }

            if (in_array($date_single, $checkinCheckout['checkin'])) {
                $title = '';
                $class = 'booked-date-full-calendar';
            }

            if (in_array($date_single, $checkinCheckout['checkout'])) {
                $title = '';
                $class = 'booked-date-full-calendar';
            }

            $new_dates[] = [
                'title'     => $title,
                'start'     => $date_single,
                'end'       => $date_single,
                'className' => $class,
            ];
        }

        return json_encode($new_dates);
    }

    /**
     * Get list of booked property IDs for a date range (via iCal).
     *
     * Legacy: Helper::getPropertyList()
     */
    public function getPropertyList(string $start_date, string $end_date): array
    {
        $day  = (int) ceil((strtotime($end_date) - strtotime($start_date)) / 86400);
        $data = Property::where('status', 'true')->get();
        $prop_ids = [];

        foreach ($data as $property) {
            $checkinCheckout = \LiveCart::iCalDataCheckInCheckOut($property->id);

            for ($i = 0; $i < $day; $i++) {
                $date = date('Y-m-d', strtotime("+{$i} day", strtotime($start_date)));

                if (in_array($date, $checkinCheckout['checkin'])) {
                    $prop_ids[] = $property->id;
                    break;
                }
            }
        }

        return $prop_ids;
    }

    /**
     * Search available Guesty properties by date range and guest count.
     *
     * Legacy: Helper::getPropertyListNew()
     */
    public function getPropertyListNew(string $start_date, string $end_date, int $total_guest = 0): array
    {
        $property_ids = [];
        $data = \GuestyApi::getSearchAvailability($start_date, $end_date, $total_guest);

        if (isset($data['results'])) {
            foreach ($data['results'] as $d) {
                if (isset($d['_id'])) {
                    $property_ids[] = $d['_id'];
                }
            }
        }

        return $property_ids;
    }

    /* ------------------------------------------------------------------ */
    /*  Utility methods (legacy parity)                                    */
    /* ------------------------------------------------------------------ */

    /**
     * Month abbreviation map.
     *
     * Legacy: Helper::getMonthListArray()
     */
    public function getMonthListArray(): array
    {
        return [
            '01' => 'JAN', '02' => 'FEB', '03' => 'MAR', '04' => 'APR',
            '05' => 'MAY', '06' => 'JUN', '07' => 'JUL', '08' => 'AUG',
            '09' => 'SEP', '10' => 'OCT', '11' => 'NOV', '12' => 'DEC',
        ];
    }

    /**
     * ISO country code list.
     *
     * Legacy: Helper::getCountryListArray()
     */
    public function getCountryListArray(): array
    {
        return [
            'AF' => 'Afghanistan', 'AX' => "\u{00C5}land Islands", 'AL' => 'Albania', 'DZ' => 'Algeria',
            'AS' => 'American Samoa', 'AD' => 'Andorra', 'AO' => 'Angola', 'AI' => 'Anguilla',
            'AQ' => 'Antarctica', 'AG' => 'Antigua and Barbuda', 'AR' => 'Argentina', 'AM' => 'Armenia',
            'AW' => 'Aruba', 'AU' => 'Australia', 'AT' => 'Austria', 'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas', 'BH' => 'Bahrain', 'BD' => 'Bangladesh', 'BB' => 'Barbados',
            'BY' => 'Belarus', 'BE' => 'Belgium', 'BZ' => 'Belize', 'BJ' => 'Benin',
            'BM' => 'Bermuda', 'BT' => 'Bhutan', 'BO' => 'Bolivia, Plurinational State of',
            'BQ' => 'Bonaire, Sint Eustatius and Saba', 'BA' => 'Bosnia and Herzegovina',
            'BW' => 'Botswana', 'BV' => 'Bouvet Island', 'BR' => 'Brazil',
            'IO' => 'British Indian Ocean Territory', 'BN' => 'Brunei Darussalam', 'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso', 'BI' => 'Burundi', 'KH' => 'Cambodia', 'CM' => 'Cameroon',
            'CA' => 'Canada', 'CV' => 'Cape Verde', 'KY' => 'Cayman Islands',
            'CF' => 'Central African Republic', 'TD' => 'Chad', 'CL' => 'Chile', 'CN' => 'China',
            'CX' => 'Christmas Island', 'CC' => 'Cocos (Keeling) Islands', 'CO' => 'Colombia',
            'KM' => 'Comoros', 'CG' => 'Congo', 'CD' => 'Congo, the Democratic Republic of the',
            'CK' => 'Cook Islands', 'CR' => 'Costa Rica', 'CI' => "C\u{00F4}te d'Ivoire",
            'HR' => 'Croatia', 'CU' => 'Cuba', 'CW' => "Cura\u{00E7}ao", 'CY' => 'Cyprus',
            'CZ' => 'Czech Republic', 'DK' => 'Denmark', 'DJ' => 'Djibouti', 'DM' => 'Dominica',
            'DO' => 'Dominican Republic', 'EC' => 'Ecuador', 'EG' => 'Egypt', 'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea', 'ER' => 'Eritrea', 'EE' => 'Estonia', 'ET' => 'Ethiopia',
            'FK' => 'Falkland Islands (Malvinas)', 'FO' => 'Faroe Islands', 'FJ' => 'Fiji',
            'FI' => 'Finland', 'FR' => 'France', 'GF' => 'French Guiana', 'PF' => 'French Polynesia',
            'TF' => 'French Southern Territories', 'GA' => 'Gabon', 'GM' => 'Gambia', 'GE' => 'Georgia',
            'DE' => 'Germany', 'GH' => 'Ghana', 'GI' => 'Gibraltar', 'GR' => 'Greece',
            'GL' => 'Greenland', 'GD' => 'Grenada', 'GP' => 'Guadeloupe', 'GU' => 'Guam',
            'GT' => 'Guatemala', 'GG' => 'Guernsey', 'GN' => 'Guinea', 'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana', 'HT' => 'Haiti', 'HM' => 'Heard Island and McDonald Islands',
            'VA' => 'Holy See (Vatican City State)', 'HN' => 'Honduras', 'HK' => 'Hong Kong',
            'HU' => 'Hungary', 'IS' => 'Iceland', 'IN' => 'India', 'ID' => 'Indonesia',
            'IR' => 'Iran, Islamic Republic of', 'IQ' => 'Iraq', 'IE' => 'Ireland',
            'IM' => 'Isle of Man', 'IL' => 'Israel', 'IT' => 'Italy', 'JM' => 'Jamaica',
            'JP' => 'Japan', 'JE' => 'Jersey', 'JO' => 'Jordan', 'KZ' => 'Kazakhstan',
            'KE' => 'Kenya', 'KI' => 'Kiribati', 'KP' => "Korea, Democratic People's Republic of",
            'KR' => 'Korea, Republic of', 'KW' => 'Kuwait', 'KG' => 'Kyrgyzstan',
            'LA' => "Lao People's Democratic Republic", 'LV' => 'Latvia', 'LB' => 'Lebanon',
            'LS' => 'Lesotho', 'LR' => 'Liberia', 'LY' => 'Libya', 'LI' => 'Liechtenstein',
            'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MO' => 'Macao',
            'MK' => 'Macedonia, the former Yugoslav Republic of', 'MG' => 'Madagascar',
            'MW' => 'Malawi', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'ML' => 'Mali',
            'MT' => 'Malta', 'MH' => 'Marshall Islands', 'MQ' => 'Martinique', 'MR' => 'Mauritania',
            'MU' => 'Mauritius', 'YT' => 'Mayotte', 'MX' => 'Mexico',
            'FM' => 'Micronesia, Federated States of', 'MD' => 'Moldova, Republic of',
            'MC' => 'Monaco', 'MN' => 'Mongolia', 'ME' => 'Montenegro', 'MS' => 'Montserrat',
            'MA' => 'Morocco', 'MZ' => 'Mozambique', 'MM' => 'Myanmar', 'NA' => 'Namibia',
            'NR' => 'Nauru', 'NP' => 'Nepal', 'NL' => 'Netherlands', 'NC' => 'New Caledonia',
            'NZ' => 'New Zealand', 'NI' => 'Nicaragua', 'NE' => 'Niger', 'NG' => 'Nigeria',
            'NU' => 'Niue', 'NF' => 'Norfolk Island', 'MP' => 'Northern Mariana Islands',
            'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PW' => 'Palau',
            'PS' => 'Palestinian Territory, Occupied', 'PA' => 'Panama',
            'PG' => 'Papua New Guinea', 'PY' => 'Paraguay', 'PE' => 'Peru', 'PH' => 'Philippines',
            'PN' => 'Pitcairn', 'PL' => 'Poland', 'PT' => 'Portugal', 'PR' => 'Puerto Rico',
            'QA' => 'Qatar', 'RE' => "R\u{00E9}union", 'RO' => 'Romania',
            'RU' => 'Russian Federation', 'RW' => 'Rwanda', 'BL' => "Saint Barth\u{00E9}lemy",
            'SH' => 'Saint Helena, Ascension and Tristan da Cunha', 'KN' => 'Saint Kitts and Nevis',
            'LC' => 'Saint Lucia', 'MF' => 'Saint Martin (French part)',
            'PM' => 'Saint Pierre and Miquelon', 'VC' => 'Saint Vincent and the Grenadines',
            'WS' => 'Samoa', 'SM' => 'San Marino', 'ST' => 'Sao Tome and Principe',
            'SA' => 'Saudi Arabia', 'SN' => 'Senegal', 'RS' => 'Serbia', 'SC' => 'Seychelles',
            'SL' => 'Sierra Leone', 'SG' => 'Singapore', 'SX' => 'Sint Maarten (Dutch part)',
            'SK' => 'Slovakia', 'SI' => 'Slovenia', 'SB' => 'Solomon Islands', 'SO' => 'Somalia',
            'ZA' => 'South Africa', 'GS' => 'South Georgia and the South Sandwich Islands',
            'SS' => 'South Sudan', 'ES' => 'Spain', 'LK' => 'Sri Lanka', 'SD' => 'Sudan',
            'SR' => 'Suriname', 'SJ' => 'Svalbard and Jan Mayen', 'SZ' => 'Swaziland',
            'SE' => 'Sweden', 'CH' => 'Switzerland', 'SY' => 'Syrian Arab Republic',
            'TW' => 'Taiwan, Province of China', 'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania, United Republic of', 'TH' => 'Thailand', 'TL' => 'Timor-Leste',
            'TG' => 'Togo', 'TK' => 'Tokelau', 'TO' => 'Tonga', 'TT' => 'Trinidad and Tobago',
            'TN' => 'Tunisia', 'TR' => 'Turkey', 'TM' => 'Turkmenistan',
            'TC' => 'Turks and Caicos Islands', 'TV' => 'Tuvalu', 'UG' => 'Uganda',
            'UA' => 'Ukraine', 'AE' => 'United Arab Emirates', 'GB' => 'United Kingdom',
            'US' => 'United States', 'UM' => 'United States Minor Outlying Islands',
            'UY' => 'Uruguay', 'UZ' => 'Uzbekistan', 'VU' => 'Vanuatu',
            'VE' => 'Venezuela, Bolivarian Republic of', 'VN' => 'Viet Nam',
            'VG' => 'Virgin Islands, British', 'VI' => 'Virgin Islands, U.S.',
            'WF' => 'Wallis and Futuna', 'EH' => 'Western Sahara', 'YE' => 'Yemen',
            'ZM' => 'Zambia', 'ZW' => 'Zimbabwe',
        ];
    }

    /**
     * Calculate number of days between two dates.
     *
     * Legacy: Helper::calculateDays() / getDayBetweenTwoDates()
     */
    public function calculateDays(string $start_date, string $end_date): int
    {
        return (int) ceil((strtotime($end_date) - strtotime($start_date)) / 86400);
    }

    /**
     * Alias for calculateDays (legacy had both).
     */
    public function getDayBetweenTwoDates(string $start_date, string $end_date): int
    {
        return $this->calculateDays($start_date, $end_date);
    }

    /**
     * Set session language.
     *
     * Legacy: Helper::languageChanger()
     */
    public function languageChanger(string $lan): void
    {
        Session::put('current_language', $lan);
    }

    /**
     * Gender dropdown options.
     */
    public function getGenderData(): array
    {
        return ['male' => 'male', 'female' => 'female', 'unisex' => 'unisex', 'kids' => 'kids'];
    }

    /**
     * Login type dropdown options.
     */
    public function getLoginTypeData(): array
    {
        return ['normal' => 'normal', 'google' => 'google', 'facebook' => 'facebook'];
    }

    /**
     * Device type dropdown options.
     */
    public function getDeviceTypeData(): array
    {
        return ['ios' => 'ios', 'A' => 'android'];
    }

    /**
     * Delete a public file (stub — kept for legacy parity).
     */
    public function deleteFile(string $file): void
    {
        if ($file && is_file(public_path($file))) {
            @unlink(public_path($file));
        }
    }
}
