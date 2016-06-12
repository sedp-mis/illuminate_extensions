<?php

namespace SedpMis\Lib\Transformer;

class Transformer
{
    const OPTIONS_SEPARATOR = ':';
    const OPTION_HOLDER     = '{ }';
    const SET_SEPARATOR     = ',';

    protected $specialOptions = [];

    public function cleanTransformationRule($transformationRule)
    {
        $transformationRule = ltrim($transformationRule);
        $holders = explode(' ', static::OPTION_HOLDER);

        $specialOptions = chars_within($transformationRule, $holders);

        if (count(head($specialOptions)) == 0) {
            return $transformationRule;
        }

        foreach (head($specialOptions) as $index => $specialOption) {
            $transformationRule = str_replace($specialOption, $optKey = "{opt_{$index}}", $transformationRule);
            $this->specialOptions[$optKey] = $specialOption;
        }

        return $transformationRule;
    }

    public function transform($transformationRule, $value)
    {
        $transformationRule = $this->cleanTransformationRule($transformationRule);
        $options            = explode(static::OPTIONS_SEPARATOR, $transformationRule);
        $transformation     = array_shift($options);
        $options            = $this->getTrueOptions($options);
        $option             = head($options);

        switch ($transformation) {
            case 'int':
            case 'integer':
                $value = (int) $value;
                break;
            case 'bool':
            case 'boolean':
                $value = (bool) $value;
                break;
            case 'string': 
                $value = $value.'';
                break;
            case 'nullable':
                $value = (empty($value)) ? null : $value;
                break;
            case 'float':
                if (is_string($value)) {
                    $value = str_replace(',', '', $value);
                }

                $value = (float) $value;
                break;
            case 'money':
                $value = number_format($value, 2);
                break;
            case 'snakecase':
            case 'snake_case':
                $value = snake_case($value);
                break;
            case 'studlycase':
            case 'studly_case':
                $value = studly_case($value);
                break;
            case 'lowercase':
                $value = strtolower($value);
                break;
            case 'uppercase':
                $value = strtoupper($value);
                break;
            case 'ucwords':
                $value = ucwords($value);
                break;
            case 'password':
                $value = \Hash::make($value);
                break;
            case 'date':
                if (!$value OR $value == '0000-00-00') {
                    $value = null;
                } else {
                    $value = date($option ?: 'Y-m-d', strtotime($value));
                }
                break;
            case 'zerofill':
                $value = str_pad($value, ( ((int)$option) ?: 5 ), 0, STR_PAD_LEFT);
                break;
            case 'callback':
            case 'function': 
                $transformation = $option;
                array_shift($options);
            default:
                array_unshift($options, $value);
                $value = call_user_func_array($transformation, $options);
                break;
        }

        return $value;
    }

    public function getTrueOptions($options)
    {
        foreach ($options as &$option) {
            if (array_key_exists($option, $this->specialOptions)) {
                $option = $this->specialOptions[$option];
                // Remove the open and close delimiter { }
                $option = substr($option, 1);
                $option = substr($option, 0, -1);
            }
        }

        return $options;
    }
}
