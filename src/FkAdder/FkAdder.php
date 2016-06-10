<?php

namespace Services\FkAdder;

use Services\Makeable\MakeableTrait;
use Illuminate\Database\Schema\Blueprint;
use Services\FkAdder\Dictionary\BaseFk;

class FkAdder
{
    use MakeableTrait;

    /**
     * Table blueprint.
     * @var Illuminate\Database\Schema\Blueprint;
     */
    protected $table;

    /**
     * Deferred fkCreators.
     * @var array
     */
    protected static $deferredFkCreators = [];

    public function __construct(Blueprint $table)
    {
        $this->table = $table;
    }

    /**
     * Add a foreign key to table and defer its foreign key creation.
     *
     * @param string $fk
     * @param string $column
     * @param string $fkName
     * @param string $onDelete
     * @param string $onUpdate
     */
    public function addFk($fk, $column = null, $fkName = null, $onDelete = null, $onUpdate = null)
    {
        $fkService = $this->makeFkService($fk);

        $column = $column ?: $fkService->defaultColumn();

        static::$deferredFkCreators[] = $fkService->makeFkCreator($column, $fkName, $onDelete, $onUpdate);

        return $fkService->createFkColumn($column);
    }

    /**
     * Execute deferred foreign keys creation.
     *
     * @return void
     */
    public static function createForeignKeys()
    {
        foreach (static::$deferredFkCreators as $fkCreator) {
            $fkCreator->createFk();
        }
    }

    /**
     * Return deferred fkCreators.
     *
     * @return array
     */
    public static function deferredFkCreators()
    {
        return static::$deferredFkCreators;
    }

    /**
     * Return the fkService for foreign key.
     *
     * @param  string $fk
     * @return \Services\FkAdder\Dictionary\BaseFk
     */
    public function makeFkService($fk)
    {
        if (!is_null(BaseFk::getFkDatatype($fk))) {
            return new BaseFk($this->table, $fk);
        }

        $class = '\Services\FkAdder\Dictionary\\'.studly_case($fk);

        return new $class($this->table);
    }
}
