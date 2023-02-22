<?php

namespace AhmetShen\PackageGenerator;

use Illuminate\Support\Facades\Facade;

class PackageGeneratorFacade extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'package-generator';
    }
}
