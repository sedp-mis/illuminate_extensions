<?php 

namespace SedpMis\Lib\IlluminateExtensions;

use Illuminate\Database\Schema\Blueprint;

class Blueprint extends Blueprint 
{    
    public function dropForeignKeys(array $foreignKeys = array())
    {
        $table = $this->getTable();
        foreach ($foreignKeys as $i => $key)
        {
            $this->dropForeign($table.'_'.$key.'_foreign');
        }
        return $this;
    }    
}
