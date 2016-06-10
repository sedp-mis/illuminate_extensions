<?php

namespace SedpMis\Lib\Models;

/**
 * A null model object
 */
class NullModel extends BaseModel
{
    public $incrementing = null;
    
    public $timestamps = null;

    public $exists = null;

    public function __get($key)
    {
        // Return new NullModel for null relation model
        if (in_array($key, array_keys($this->relations))) {
            return new static;
        }

        return null;
    }

    public function toArray()
    {
        return null;
    }
}
