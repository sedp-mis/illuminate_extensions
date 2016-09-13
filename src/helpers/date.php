<?php

/*
 * Date Helpers
 */
if (!function_exists('carbon')) {
    /**
     * Convert or return new carbon instance.
     *
     * @param  string|mixed   $datetime
     * @return \Carbon\Carbon
     */
    function carbon($datetime = null)
    {
        if ($datetime instanceof \Carbon\Carbon) {
            return $datetime;
        }

        if ($datetime instanceof \DateTime) {
            $datetime = $datetime->format('Y-m-d H:i:s');
        }

        return new \Carbon\Carbon($datetime);
    }
}

if (!function_exists('sql_date')) {
    /**
     * Convert date to mysql date format.
     *
     * @param  string $date
     * @return string
     */
    function sql_date($date)
    {
        return $date ? date('Y-m-d', strtotime($date)) : null;
    }
}
