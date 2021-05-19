<?php

namespace Laracle\ShopBox\Facades;

use Illuminate\Support\Facades\Facade;

class ShopBox extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'shopbox';
    }
}
