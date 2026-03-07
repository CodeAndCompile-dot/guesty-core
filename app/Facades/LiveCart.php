<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array DifferentDates(string $start, string $end, string $format = 'Y-m-d')
 * @method static array iCalDataCheckInCheckOut(int $propertyId)
 * @method static array iCalDataCheckInCheckOutCheckinCheckout(int $propertyId)
 * @method static int refreshIcalData(int $propertyId, string $icalLink, int $importListId)
 * @method static void allIcalImportListRefresh()
 * @method static void getFileIcalFileData(int $propertyId)
 * @method static void getEventsICalObject(int $propertyId)
 *
 * @see \App\Helpers\LiveCart
 */
class LiveCart extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'LiveCart';
    }
}
