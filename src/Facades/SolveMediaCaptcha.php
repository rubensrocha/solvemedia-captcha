<?php

namespace Rubensrocha\SolveMediaCaptcha\Facades;

use Illuminate\Support\Facades\Facade;

class SolveMediaCaptcha extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'solvemediacaptcha';
    }
}
