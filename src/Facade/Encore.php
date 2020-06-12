<?php

namespace Innocenzi\LaravelEncore\Facade;

use Illuminate\Support\Facades\Facade;

class Encore extends Facade
{
    protected static function getFacadeAccessor()
    {
        return static::class;
    }
}
