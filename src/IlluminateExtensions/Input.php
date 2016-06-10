<?php 

namespace SedpMis\Lib\IlluminateExtensions;

use Illuminate\Support\Facades\Input as IlluminateInput;

class Input extends IlluminateInput
{
    public static function all($withToken = false)
    {
        $inputs = static::$app['request']->all();

        if (!$withToken) {
            unset($inputs['_token']);
        }

        return $inputs;
    }
}
