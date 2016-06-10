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

/**
 *
 * Program/Function Name:      NumToWords
 * Filename:                   numtowords.php
 * Description:                Convert and return integer number into words. Range: 100 Billion
 * Usage:          
 *             
 *             num_to_words($integer_value);
 *             
 *     
 * Author/Programmer:          Arjon Jason A. Castro 
 * Email Add:                  ajcastro29@gmail.com
 * Contact No.:                +639099258893
 * Address:                    096 Purok 2 Bañag, Daraga, Albay, 4501, Philippines
 * Date:                       April 20, 2013
 */

function NumWord($digit)
{
    $word = array(
        'Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 
        'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'
    );

    return $word[$digit];
}
function SecNumWord($sec_num)
{
    $sec_num_word = array(
        'Twenty', 'Thirty', 'Fourty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'
    );

    return $sec_num_word[$sec_num - 2];
}
function ToWords($value)
{
    $num_in_words = "";
    if (isset($value[2]) and $value[0] != 0) {
        $num_in_words .= NumWord($value[0]) . " Hundred ";
    }
    if (isset($value[1]) and $value[strlen($value) - 2] > 1) {
        $num_in_words .= SecNumWord($value[strlen($value) - 2]) . " ";
    }
    if (isset($value[1]) and $value[strlen($value) - 2] == 1) {
        $num_in_words .= NumWord(IntVal($value[strlen($value) - 2] . $value[strlen($value) - 1]));
    }
    if ((!(isset($value[1]) and $value[strlen($value) - 2] == 1) and $value[strlen($value) - 1] != 0) or strlen($value) == 1) {
        $num_in_words .= NumWord($value[strlen($value) - 1]) . "";
    }
    
    return $num_in_words;
}
/**
 * Convert integer into words.
 *
 * @param $num integer Integer number to be converted
 */
function num_to_words($num)
{
    list($num) = explode('.', $num . ".0");
    $string       = strrev("$num");
    $num_in_words = "";
    $place        = "";
    
    for ($i = 0; $i < strlen($string); $i += 3) {
        if ($i >= 9) {
            $place = 'Billion';
        } else if ($i >= 6) {
            $place = 'Million';
        } else if ($i >= 3) {
            $place = 'Thousand';
        }
        if ((isset($string[$i + 2]) and $string[$i + 2] != 0) or (isset($string[$i + 1]) and $string[$i + 1] != 0) or (isset($string[$i]) and $string[$i] != 0)) {
            $num_in_words = ToWords(strrev(substr($string, $i, 3))) . " $place " . $num_in_words;
        }
    }
    
    return rtrim($num_in_words);
}

/**
 * Convert money into words.
 *
 * @param  float  $amount
 * @param  string  $currency
 * @param  boolean $includeOnlySuffix
 * @return string
 */
function money_to_words($amount, $currency = 'Pesos', $includeOnlySuffix = true)
{
    list($whole, $cents) = explode('.', $amount . ".00");
    
    if (strlen($cents) < 2)
        $cents .= '0';
    else
        $cents = substr($cents, 0, 2);
    
    $centLabel = ($cents > 1) ? 'Centavos' : 'Centavo';
    $wholeStr  = num_to_words($whole);
    $centsStr  = (intval($cents) > 0) ? 'And ' . num_to_words($cents) . " {$centLabel} " : '';
    
    return "{$wholeStr} {$currency} {$centsStr}" . ($includeOnlySuffix ? 'Only' : '');
}

// // Sample Run: Number to Words From 100 up to 1500
// for($i=0;$i<=100;$i++)
//  echo "$i -> ".num_to_words("$i")."\n";        


if(! function_exists('num_zero_pad')) 
{
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
    function ucwords_except($string, array $except = NULL)
    {
        $except_locator = '&*!-';

        $default_except = ['and', 'of', 'for'];

        $except = array_merge($default_except, $except ?: []);

        foreach ($except as $i => $except_value) 
        {
            $string = str_replace(' '.$except_value.' ', $except_locator.$i.' ', $string);
        }

        $string = ucwords($string);

        foreach ($except as $i => $except_value) 
        {
            $string = str_replace($except_locator.$i, ' '. $except_value.' ', $string);    
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
     * @param  string  $str
     * @param  integer $count
     * @return string
     */
    function remove_last_char($str, $count = 1)
    {
        return substr($str, 0, ($count * -1));
    }
}

if(!function_exists('utf8'))
{
    /**
     * Convert special characters to UTF8 format.
     *
     * @param  string   $text   The text to be converted.
     * @return string
     */
    function utf8($text)
    {
        try {
            return iconv('UTF-8', 'windows-1252', $text);
        } catch(\ErrorException $e) {
            return $text;
        }
    }
}

if ( ! function_exists('println')) {
    /**
     * Print with new line.
     *
     * @param  mixed $data
     * @return void
     */
    function println($data)
    {
        foreach(func_get_args() as $data)
        {
            print_r($data);
            print("\n");
        }
    }
}

if ( ! function_exists('is_plural')) {
    /**
     * Determine whether string is plural.
     *
     * @param  string  $string
     * @return boolean
     */
    function is_plural($string)
    {
        return str_plural($string) === $string;
    }
}

if ( ! function_exists('is_singular')) {
    /**
     * Determine whether string is singular.
     *
     * @param  string  $string
     * @return boolean
     */
    function is_singular($string)
    {
        return ! is_plural($string);
    }
}

if( ! function_exists('replace_extension'))
{
    /**
     * Replace filename extension.
     *
     * @param  string $filename
     * @param  string $new_extension
     * @return string
     */
    function replace_extension($filename, $new_extension) {
        $info = pathinfo($filename);
        return $info['filename'] . '.' . $new_extension;
    }
}

if (!function_exists('left')) {
    /**
     * Get the first N characters from left.
     *
     * @param  string $str
     * @param  int $length
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
     * @param  int $length
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
     * @param  int $number
     * @return string
     */
    function space($number) {
        return str_repeat(" ", $number);
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
     * @param  string  $str
     * @param  int  $len
     * @param  boolean $fromStart
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
        $openDelim = head($delims);
        $closeDelim = end($delims);

        preg_match_all('/'.$openDelim.'([A-Za-z0-9_ ]+?)'.$closeDelim.'/', $string, $result);

        return $result;
    }
}
