<?php

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
