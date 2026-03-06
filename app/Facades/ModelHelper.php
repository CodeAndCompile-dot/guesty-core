<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string|null getDataFromSetting(string $name)
 *
 * @see \App\Helpers\ModelHelper
 */
class ModelHelper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'ModelHelper';
    }
}
