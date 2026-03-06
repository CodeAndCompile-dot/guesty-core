<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getBooleanDataActual()
 * @method static array getBooleanData()
 * @method static array getfirstTrueBooleanData()
 * @method static array getWeekNameSelect()
 * @method static array getPropertyStatus()
 * @method static array getTempletes()
 * @method static array getTownList()
 * @method static array getCoupanCodeList()
 * @method static array getTypeOfField()
 * @method static string getSeoUrlGet(string $title)
 * @method static string getImage(string $image)
 *
 * @see \App\Helpers\Helper
 */
class Helper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'Helper';
    }
}
