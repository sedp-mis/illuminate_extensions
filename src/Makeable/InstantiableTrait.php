<?php

namespace SedpMis\Lib\Makeable;

/**
 * Alias of MakeableTrait. Solution for factory classes which has non-static method make().
 */
trait InstantiableTrait
{
    /**
     * Instantiate or make new instance via static method,  can passed any number of parameters.
     *
     * @return static
     */
    public static function instantiate()
    {
        return (new \ReflectionClass(get_called_class()))->newInstanceArgs(func_get_args());
    }

    /**
     * Alias of instantiate() method.
     *
     * @return static
     */
    public static function instance()
    {
        return static::instantiate();
    }
}
