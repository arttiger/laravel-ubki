<?php

namespace Arttiger\Ubki\Facades;

use Illuminate\Support\Facades\Facade;

class Ubki extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ubki';
    }
}
