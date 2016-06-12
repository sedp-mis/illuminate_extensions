<?php

use Illuminate\Database\Eloquent\Builder;

namespace SedpMis\Lib\Models;

class EloquentBuilder extends Builder
{
    /**
     * Override. Find a model by its primary key or throw an exception.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|static
     *
     * @throws \ModelNotFoundException
     */
    public function findOrFail($id, $columns = array('*'))
    {
        if (!is_null($model = $this->find($id, $columns))) {
            return $model;
        }

        throw (new ModelNotFoundException)->setModel(get_class($this->model), $id);
    }
}
