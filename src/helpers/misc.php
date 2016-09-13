<?php

if (!function_exists('coalesce')) {
    /**
     * Get the first non-null from the parameters.
     *
     * @param  mixed $values
     * @return mixed
     */
    function coalesce($values)
    {
        $values = func_get_args();

        foreach ($values as $value) {
            if (!is_null($value)) {
                return $value;
            }
        }
    }
}

if (!function_exists('get_static')) {
    /**
     * Get static property value of a class.
     *
     * @param  string $class
     * @param  string $static
     * @return mixed
     */
    function get_static($class, $static)
    {
        return (new ReflectionClass($class))->getStaticPropertyValue($static);
    }
}

if (!function_exists('set_static')) {
    /**
     * Get static property value of a class.
     *
     * @param string $class
     * @param string $key
     * @param mixed $value
     */
    function set_static($class, $key, $value)
    {
        (new ReflectionClass($class))->setStaticPropertyValue($key, $value);
    }
}

if (!function_exists('ddr')) {
    /**
     * Dump-die-readable.
     *
     * @param mixed $data
     */
    function ddr($data)
    {
        foreach(func_get_args() as $data)
        {
            if ($data === false) {
                return;
            }

            if (is_object($data) && method_exists($data, 'toArray'))
                print_r($data->toArray());
            else
                print_r($data);

            print("\n");
        }
        
        die();
    }
}
