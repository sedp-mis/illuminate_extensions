<?php 

namespace SedpMis\Lib\Makeable;

trait MakeableTrait {

    /**
     * Instantiate or make new instance via static method,  can passed any number of parameters.
     *
     * @return static
     */
    public static function make()
    {
        return (new \ReflectionClass(get_called_class()))->newInstanceArgs(func_get_args());
    }
}
