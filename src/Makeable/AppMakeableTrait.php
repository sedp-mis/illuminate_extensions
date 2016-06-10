<?php

namespace SedpMis\Lib\Makeable;

use App;

trait AppMakeableTrait
{
    /**
     * Makeabletrait using the \App::make() to auto-inject dependencies of a class.
     *
     * @return static
     */
    public static function make()
    {
        return App::make(static::class);
    } 
}
