<?php

namespace Laracle\ShopBox\Facades;

use Illuminate\Support\Facades\Facade;
use Illuminate\Http\Request;

class Cart extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cart';
    }

}
