<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Specification\Label;

abstract class TableAction
{
    protected Label $tableName;

    public function __construct(Label $tableName)
    {
        $this->tableName = $tableName;
    }

    public function getTableName(): Label
    {
        return $this->tableName;
    }
}
