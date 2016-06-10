<?php

if(! function_exists('num_zero_pad')) {
    /**
     * Pad numbers with zeros.
     *
     * @param  int $num
     * @param  int $length
     * @return string
     */
    function num_zero_pad($num, $length)
    {
        return str_pad($num, $length, '0', STR_PAD_LEFT);
    }
}
