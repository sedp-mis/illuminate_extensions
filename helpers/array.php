<?php

/*
 * Array Helpers
 */
if (!function_exists('array_is_assoc')) {
    /**
     * Determine if array is associative(string) array.
     * 
     * @param  array $arr
     * @return bool
     */
    function array_is_assoc($arr)
    {
        if (!is_array($arr) || count($arr) === 0) {
            return;
        }

        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}

if (!function_exists('array_pluck_unique')) {
    /**
     * Pluck unique array.
     * 
     * @param  array  $arr
     * @param  string $key
     * @return array
     */
    function array_pluck_unique(array $arr, $key)
    {
        return array_unique(array_pluck($arr, $key));
    }
}

if (!function_exists('array_column_unique')) {
    /**
     * Pluck unique array using array_column().
     * 
     * @param  array  $arr
     * @param  string $key
     * @return array
     */
    function array_column_unique(array $arr, $key)
    {
        return array_unique(array_column($arr, $key));
    }
}

if (!function_exists('array_take_first')) {
    /**
     * Take first element which pass the predicate callback and remove it from array.
     *
     * @param  array         &$array
     * @param  callable|null $callback
     * @param  mixed|null    $default
     * @return mixed
     */
    function array_take_first(&$array, callable $callback = null, $default = null)
    {
        if (is_null($callback)) {
            return count($array) > 0 ? array_shift($array) : null;
        }

        foreach ($array as $i => $item) {
            if ($callback($item, $i)) {
                unset($array[$i]);

                return $item;
            }
        }

        return $default;
    }
}

if (!function_exists('array_start_from')) {
    /**
     * Return elements of array starting from an element which first passes the predicate callback.
     *
     * @param  array|mixed   $array
     * @param  callable $predicate
     * @return array
     */
    function array_start_from($array, callable $predicate)
    {
        $start    = false;
        $newArray = [];
        foreach ($array as $i => $item) {
            if (!$start && $predicate($item, $i)) {
                $start = true;
            }

            if ($start) {
                $newArray[] = $item;
            }
        }

        return $newArray;
    }
}
