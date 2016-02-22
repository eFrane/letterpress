<?php

namespace EFrane\Letterpress\Providers;

use Illuminate\Support\Facades\Facade;

class LaravelFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'letterpress';
    }
}
