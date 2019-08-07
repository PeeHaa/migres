<?php declare(strict_types=1);

namespace PeeHaa\Migres\Retrospection;

use PeeHaa\Migres\Specification\Label;

final class ColumnInformation
{
    private Label $tableName;

    private Label $columnName;

    private ColumnDefinition $columnDefinition;

    public function __construct(Label $tableName, Label $columnName, ColumnDefinition $columnDefinition)
    {
        $this->tableName        = $tableName;
        $this->columnName       = $columnName;
        $this->columnDefinition = $columnDefinition;
    }

    public function getTableName(): Label
    {
        return $this->tableName;
    }

    public function getColumnName(): Label
    {
        return $this->columnName;
    }

    public function getColumnDefinition(): ColumnDefinition
    {
        return $this->columnDefinition;
    }
}
