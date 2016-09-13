<?php

namespace SedpMis\Lib\JqGrid;

trait QueryableTrait
{
    protected $query;

    public function __call($method, $args)
    {
        call_user_func_array([$this->query, $method], $args);

        return $this;
    }
}
