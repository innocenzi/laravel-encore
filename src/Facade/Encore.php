<?php

namespace Innocenzi\LaravelEncore\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string getLinkTags(string $entryName)
 * @method static string getScriptTags(string $entryName, bool $nodefer)
 */
class Encore extends Facade
{
    protected static function getFacadeAccessor()
    {
        return static::class;
    }
}
