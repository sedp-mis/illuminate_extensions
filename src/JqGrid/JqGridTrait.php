<?php 

namespace SedpMis\Lib\JqGrid;

/**
 * Trait for generating data for jqGrid tables.
 */
trait JqGridTrait {

    /**
     * @var
     */
    protected $jqGridQuery;

    /**
     * Find corresponding field that will be accessed in database
     *
     * @param $field
     * @return string
     */
    private function getJqSearchField($field)
    {
        // jqSearchable is not initialized
        if (!isset($this->jqSearchable) || !is_array($this->jqSearchable))
            return $field;

        return isset($this->jqSearchable[$field]) ? $this->jqSearchable[$field] : $field;
    }

    private function isExcluded($field)
    {
        if( !isset($this->jqExcludedField) || !is_array($this->jqExcludedField))
            return FALSE;

        foreach ($this->jqExcludedField as $f) // TODO: (100) check if can be replaced by in_array php native function
        {
            if($field == $f)
                return TRUE;
        }

        return FALSE;
    }

    /**
     * Find corresponding field that will be accessed in database
     *
     * @param $field
     * @return $string
     */
    private function getJqFilterField($field)
    {
        if( ! isset($this->jqFilterFields) || ! is_array($this->jqFilterFields))
            $this->jqFilterFields = $this->jqSearchable;

        if( ! isset($this->jqFilterFields) || ! is_array($this->jqFilterFields))
            return $field;

        return isset($this->jqFilterFields[$field]) ? $this->jqFilterFields[$field] : $field;
    }

    /**
     * Build query for the search
     *
     * @return $this
     */
    private function jqSearchQuery()
    {
        $searchFilters = ($filters = \Input::get('filters')) ? $filters : NULL;

        // not valid search filter
        if (!($searchFilters != NULL) || (!isset($searchFilters['rules'])) || (!is_array($searchFilters['rules']))) {
            return $this;
        }

        $this->setJqGridQuery($this->jqGridQuery->where(function ($query) use ($searchFilters) {
            foreach ($searchFilters['rules'] as $rule) {
                $field = $this->getJqSearchField($rule['field']);

                if (is_array($field)) {
                    $raw = "CONCAT(";
                    $count = 0;
                    foreach ($field as $f) {
                        if( ! $this->isExcluded($f)) {
                            $raw .= ($count) ? "," : "";
                            $raw .= "{$f}";
                            $count++;
                        }
                    }
                    $raw .= ")";

                    $data = str_replace(' ', '', $rule['data']);
                    $data = str_replace(',', '', $data);
                    $rArr = str_split($data);

                    $rImp = implode('%', $rArr);
                    $query->orWhere(\DB::raw($raw), 'LIKE', "%{$rImp}%");
                } else if($field){
                    if( ! $this->isExcluded($field))
                        $query->orWhere($field, 'LIKE', "%{$rule['data']}%");
                }
            }
        }));

        return $this;
    }

    /**
     * Build query for filters
     *
     * @return $this
     */
    private function jqFilterQuery()
    {
        if( ($filter = \Input::get('filter')) && is_array($filter))
        {
            $this->setJqGridQuery($this->jqGridQuery->where(function($query) use ($filter) {
                foreach ($filter as $k=>$val)
                {
                    $field = $this->getJqFilterField($k);

                    if(is_array($val))
                    {
                        $query->where(function($query) use ($val, $field) {
                            foreach($val as $v)
                            {
                                if( ! $this->isExcluded($v))
                                    $query->orWhere($field, '=', $v);
                            }
                        });
                    }
                    else
                    {
                        if( ! $val)
                        {
                            if( ! $this->isExcluded($val))
                                $query->where($field, '=', NULL);
                        } else
                        {
                            if( ! $this->isExcluded($val))
                                $query->where($field, '=', $val);
                        }
                    }
                }
            }));
        }
        return $this;
    }

    /**
     * Build query for order
     *
     * @return $this
     */
    private function jqOrderQuery()
    {
        if ($orderIndex = \Input::get('sidx')) {
            $this->setJqGridQuery($this->jqGridQuery->orderBy($this->getJqSearchField($orderIndex), \Input::get('sord')) ? : 'asc');
        }

        return $this;
    }

    public function setJqSearchable($jqSearchable = [])
    {
        $this->jqSearchable = $jqSearchable;
        return $this;
    }

    public function setJqFilterFields($jqFilterFields = [])
    {
        $this->jqFilterFields = $jqFilterFields;
        return $this;
    }

    /**
     * Get current jqgrid query
     *
     * @return mixed
     */
    public function getJqGridQuery()
    {
        return $this->jqGridQuery;
    }

    /**
     * Set jqgrid query to be used
     *
     * @param mixed $query
     * @return $this
     */
    public function setJqGridQuery($query)
    {
        $this->jqGridQuery = $query;

        return $this;
    }

    /**
     * Return formatted data to be parsed by jqgrid
     *
     * @return mixed
     */
    public function makeJqGrid()
    {

        $data = $this->jqSearchQuery()->jqFilterQuery()->jqOrderQuery()->getJqGridQuery()->paginate((\Input::get('rows') ? : 15));

        $out = $data->getCollection()->toArray();

        return [
            'Page'       => $data->getCurrentPage(),
            'Total'      => ceil($data->getTotal() / $data->getPerPage()),
            'Records'    => $data->getTotal(),
            'SortColumn' => \Input::get('sidx'),
            'SortOrder'  => \Input::get('sord') ? : "asc",
            'Data'       => $out
        ];
    }
}