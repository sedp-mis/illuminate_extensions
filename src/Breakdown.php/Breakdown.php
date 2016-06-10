<?php

namespace SedpMis\Lib\Breakdown;

use SedpMis\Lib\Makeable\MakeableTrait;

class Breakdown
{
    use MakeableTrait;

    /**
     * Array config of breakdown.
     *
     * @immutable
     * @var array
     */
    protected $config;

    public function __construct(array $config)
    {
        // Sample config for money breakdown
        //     '1000' => 1000,
        //     '500'  => 500,
        //     '200'  => 200,
        //     '100'  => 100,
        //     '50'   => 50,
        //     '20'   => 20,
        //     '10'   => 10,
        //     '5'    => 5,
        //     '1'    => 1

        $this->config = $config;
    }

    public function breakdown($input)
    {
        $output    = [];
        $remainder = null;

        foreach ($this->config as $key => $breakdownValue) {
            $remainder = $remainder !== null ? $remainder : $input;

            $output[$key] = 0;

            if ($remainder >= $breakdownValue) {
                $output[$key] = intval($remainder / $breakdownValue);
                $remainder    = $remainder - $output[$key] * $breakdownValue;
            }
        }

        return $output;
    }
}
