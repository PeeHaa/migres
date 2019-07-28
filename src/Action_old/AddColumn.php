<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action_old;

use PeeHaa\Migres\Column;
use PeeHaa\Migres\Migration\Queries;

final class AddColumn implements Action
{
    private Column $column;

    public function __construct(Column $column)
    {
        $this->column = $column;
    }

    public function getColumn(): Column
    {
        return $this->column;
    }

    public function toQueries(string $tableName): Queries
    {
        return new Queries(sprintf('ALTER TABLE "%s" ADD COLUMN %s', $tableName, $this->column->toSql()));
    }
}
