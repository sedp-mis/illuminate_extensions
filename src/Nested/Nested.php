<?php

namespace SedpMis\Lib\Nested;

/**
 * For nested collections.
 */
class Nested
{
    /**
     * Make nested data collection.
     *
     * @param  mixed    $collection   Collection to be nested. Make sure to ordery by, level and position
     * @param  callable $formatNode   Format callback
     * @param  string   $pk           Primary key name (id)
     * @param  string   $fk           Foreign key name (parent_id)
     * @param  string   $childrenAttr Children attribute to be used (children)
     * @param  string   $levelLabel   Level label (level)
     * @return array    The nested collection
     */
    public static function make($collection, callable $formatNode = null, $pk = 'id', $fk = 'parent_id', $childrenAttr = 'children', $levelLabel = 'level')
    {
        $nested   = [];
        $onLevels = [];
        foreach ($collection as $item) {
            $itemNode                                 = $item;
            $onLevels[$item[$levelLabel]][$item[$pk]] = $itemNode;
        }

        $start = count($onLevels) - 1;

        for ($level = $start; $level >= 0; $level--) {
            $itemsOnCurrentLevel = $onLevels[$level];

            foreach ($itemsOnCurrentLevel as $i => $childNode) {
                $parentLevel = $childNode[$levelLabel] - 1;
                $parentId    = $childNode[$fk];

                if (!isset($onLevels[$parentLevel][$parentId]) && $level > 0) {
                    throw new \Exception('Error: Cannot find '.$fk.' of item having '.$pk.' of '.$childNode[$pk]);
                }

                if (isset($formatNode)) {
                    $childNode = $formatNode($childNode);
                }

                if ($level > 0) {
                    $onLevels[$parentLevel][$parentId][$childrenAttr][] = $childNode;
                    unset($onLevels[$level]);
                } else {
                    $nested[] = $childNode;
                }
            }
        }

        return $nested;
    }
}
