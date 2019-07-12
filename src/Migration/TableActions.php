<?php declare(strict_types=1);

namespace PeeHaa\Migres\Migration;

final class TableActions
{
    private string $tableName;

    private Actions $actions;

    public function __construct(string $tableName, Actions $actions)
    {
        $this->tableName = $tableName;
        $this->actions   = $actions;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function getActions(): Actions
    {
        return $this->actions;
    }
}
