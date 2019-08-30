<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Migration\Queries;
use PeeHaa\Migres\Specification\Label;

final class RemoveColumnComment extends TableAction implements Action
{
    private Label $columnName;

    public function __construct(Label $tableName, Label $columnName)
    {
        parent::__construct($tableName);

        $this->columnName = $columnName;
    }

    public function toQueries(): Queries
    {
        return new Queries(
            sprintf('COMMENT ON COLUMN "%s"."%s" IS NULL', $this->tableName->toString(), $this->columnName->toString()),
        );
    }
}
