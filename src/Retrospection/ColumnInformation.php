<?php declare(strict_types=1);

namespace PeeHaa\Migres\Retrospection;

final class ColumnInformation
{
    private string $tableName;

    private string $columnName;

    private ColumnDefinition $columnDefinition;

    public function __construct(string $tableName, string $columnName, ColumnDefinition $columnDefinition)
    {
        $this->tableName        = $tableName;
        $this->columnName       = $columnName;
        $this->columnDefinition = $columnDefinition;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function getColumnName(): string
    {
        return $this->columnName;
    }

    public function getColumnDefinition(): ColumnDefinition
    {
        return $this->columnDefinition;
    }
}
