<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Constraint\TableConstraint;
use PeeHaa\Migres\Migration\Queries;

final class AddUniqueConstraint extends TableAction implements Action
{
    private TableConstraint $constraint;

    public function __construct(string $tableName, TableConstraint $constraint)
    {
        parent::__construct($tableName);

        $this->constraint = $constraint;
    }

    public function getConstraint(): TableConstraint
    {
        return $this->constraint;
    }

    public function toQueries(): Queries
    {
        return new Queries(sprintf('ALTER TABLE "%s" ADD %s', $this->tableName, $this->constraint->toSql()));
    }
}
