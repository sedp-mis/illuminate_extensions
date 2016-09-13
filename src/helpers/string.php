<?php

/*
 * String Helpers
 */
if (!function_exists('ucwords_except')) {
    /**
     * Call ucwords() function except for some string.
     *
     * @param  string     $string
     * @param  array|null $except
     * @return string
     */
    function ucwords_except($string, array $except = null)
    {
        $except_locator = '&*!-';

        $default_except = ['and', 'of', 'for'];

        $except = array_merge($default_except, $except ?: []);

        foreach ($except as $i => $except_value) {
            $string = str_replace(' '.$except_value.' ', $except_locator.$i.' ', $string);
        }

        $string = ucwords($string);

        foreach ($except as $i => $except_value) {
            $string = str_replace($except_locator.$i, ' '.$except_value.' ', $string);
        }

        return $string;
    }
}

if (!function_exists('remove_whitespaces')) {
    /**
     * Remove whitespaces.
     *
     * @param  string $str
     * @return string
     */
    function remove_whitespaces($str)
    {
        return preg_replace('/\s/', '', $str);
    }
}

if (!function_exists('remove_last_char')) {
    /**
     * Remove last character(s).
     *
     * @param  string $str
     * @param  int    $count
     * @return string
     */
    function remove_last_char($str, $count = 1)
    {
        return substr($str, 0, ($count * -1));
    }
}

if (!function_exists('utf8')) {
    /**
     * Convert special characters to UTF8 format.
     *
     * @param  string $text The text to be converted
     * @return string
     */
    function utf8($text)
    {
        try {
            return iconv('UTF-8', 'windows-1252', $text);
        } catch (\ErrorException $e) {
            return $text;
        }
    }
}

if (!function_exists('println')) {
    /**
     * Print with new line.
     *
     * @param  mixed $data
     * @return void
     */
    function println($data)
    {
        foreach (func_get_args() as $data) {
            print_r($data);
            echo "\n";
        }
    }
}

if (!function_exists('is_plural')) {
    /**
     * Determine whether string is plural.
     *
     * @param  string $string
     * @return bool
     */
    function is_plural($string)
    {
        return str_plural($string) === $string;
    }
}

if (!function_exists('is_singular')) {
    /**
     * Determine whether string is singular.
     *
     * @param  string $string
     * @return bool
     */
    function is_singular($string)
    {
        return !is_plural($string);
    }
}

if (!function_exists('replace_extension')) {
    /**
     * Replace filename extension.
     *
     * @param  string $filename
     * @param  string $new_extension
     * @return string
     */
    function replace_extension($filename, $new_extension)
    {
        $info = pathinfo($filename);

        return $info['filename'].'.'.$new_extension;
    }
}

if (!function_exists('left')) {
    /**
     * Get the first N characters from left.
     *
     * @param  string $str
     * @param  int    $length
     * @return string
     */
    function left($str, $length)
    {
        return substr($str, 0, $length);
    }
}

if (!function_exists('right')) {
    /**
     * Get the first N characters from right.
     *
     * @param  string $str
     * @param  int    $length
     * @return string
     */
    function right($str, $length)
    {
        return substr($str, -$length);
    }
}

if (!function_exists('space')) {
    /**
     * Add N spaces.
     *
     * @param  int    $number
     * @return string
     */
    function space($number)
    {
        return str_repeat(' ', $number);
    }
}

if (!function_exists('clean_string')) {

    /**
     * Clean a string.  Remove special characters. Replace # with '' and / with _.
     * Use in naming menu.
     *
     * @param  string $name
     * @return string
     */
    function clean_string($name)
    {
        $name = preg_replace("/\/:[a-z]*/", '', trim(strtolower($name)));
        $name = str_replace('#', '', $name);
        $name = str_replace('/', '_', $name);

        return $name;
    }
}

if (!function_exists('to_numeric')) {
    /**
     * Convert string to numeric.
     *
     * @param  string $str
     * @return mixed
     */
    function to_numeric($str)
    {
        if (!is_numeric($str)) {
            return $str;
        }

        if (str_contains($str, '.')) {
            return (float) $str;
        }

        return (int) $str;
    }
}

if (!function_exists('first_word')) {
    /**
     * Get first word.
     *
     * @param  string $str
     * @return string
     */
    function first_word($str)
    {
        return explode(' ', trim($str))[0];
    }
}

if (!function_exists('get_chars')) {
    /**
     * Get N characters from left(start) or right side.
     *
     * @param  string $str
     * @param  int    $len
     * @param  bool   $fromStart
     * @return string
     */
    function get_chars($str, $len, $fromStart = true)
    {
        $start = $fromStart ? 0 : -1 * $len;

        return substr($str, $start, $len);
    }
}

if (!function_exists('chars_within')) {
    /**
     * Get characters within a given delimiters.
     *
     * @param  string $string
     * @param  array  $delims
     * @return string
     */
    function chars_within($string, array $delims)
    {
        $openDelim  = head($delims);
        $closeDelim = end($delims);

        preg_match_all('/'.$openDelim.'([A-Za-z0-9_ ]+?)'.$closeDelim.'/', $string, $result);

        return $result;
    }
}
