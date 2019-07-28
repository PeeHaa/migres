<?php declare(strict_types=1);

namespace PeeHaa\Migres\Exception;

class ColumnDefinitionNotFound extends Retrospection
{
    public function __construct(string $tableName, string $columnName)
    {
        parent::__construct(sprintf('Could not find definition of column `%s.%s`.', $tableName, $columnName));
    }
}
