<?php

/*
 * COMPILED HELPERS
 */

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


/*
 * Collection Helpers
 */
if (!function_exists('collection')) {
    /**
     * Create items into new collection.
     *
     * @param  array|mixed $items
     * @return \SedpMis\Lib\IlluminateExtensions\Collection
     */
    function collection($items = null)
    {
        return \SedpMis\Lib\IlluminateExtensions\Collection::make($items);
    }
}
if (!function_exists('is_collection')) {
    /**
     * Determine if is instance of collection.
     *
     * @param  mixed  $var
     * @return boolean
     */
    function is_collection($var)
    {
        return $var instanceof \Illuminate\Support\Collection;
    }
}


/*
 * Miscellaneous Functions/Methods
 */
if (!function_exists('call')) {
    /**
     * Call a class method.
     *
     * @param  string $className Name of the class
     * @param  string $method    Method to call
     * @param  array  $params    Method parameters (depends on the method to call)
     * @return mixed
     */
    function call($className, $method, $params = [])
    {
        $class = \App::make($className);

        return call_user_func_array([$class, $method], $params);
    }
}

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


/*
 * Query Helpers
 */
if (!function_exists('db_raw')) {
    /**
     * Return a db raw expression.
     *
     * @param  mixed $expression
     * @return \Illuminate\Database\Query\Expression
     */
    function db_raw($expression)
    {
        return \Illuminate\Database\DatabaseManager::raw($expression);
    }
}

if (!function_exists('joiner')) {
    /**
     * Return a joiner instance.
     *
     * @param  mixed $query
     * @return mixed
     */
    function joiner($query)
    {
        return new \SedpMis\Lib\Query\Joiner($query);
    }
}

if (!function_exists('drop_foreign_keys')) {
    /**
     * Drop foreign keys.
     *
     * @param  mixed $table
     * @param  array $foreignKeys
     * @return void
     */
    function drop_foreign_keys($table, array $foreignKeys)
    {
        $tableName = $table->getTable();
        foreach ($foreignKeys as $i => $key)
        {
            $table->dropForeign($tableName.'_'.$key.'_foreign');
        }
    }
}