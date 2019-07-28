<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

abstract class TableAction
{
    protected string $tableName;

    public function __construct(string $tableName)
    {
        $this->tableName = $tableName;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }
}
