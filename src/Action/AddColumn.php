<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Migration\Queries;
use PeeHaa\Migres\Specification\Column;
use PeeHaa\Migres\Specification\Label;

final class AddColumn extends TableAction implements Action
{
    private Column $column;

    public function __construct(Label $tableName, Column $column)
    {
        parent::__construct($tableName);

        $this->column = $column;
    }

    public function getColumn(): Column
    {
        return $this->column;
    }

    public function toQueries(): Queries
    {
        return new Queries(
            sprintf('ALTER TABLE "%s" ADD COLUMN %s', $this->tableName->toString(), $this->column->toSql()),
        );
    }
}
