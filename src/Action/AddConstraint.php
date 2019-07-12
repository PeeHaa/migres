<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Constraint\TableConstraint;
use PeeHaa\Migres\Migration\Queries;

final class AddConstraint implements Action
{
    private TableConstraint $constraint;

    public function __construct(TableConstraint $constraint)
    {
        $this->constraint = $constraint;
    }

    public function getConstraint(): TableConstraint
    {
        return $this->constraint;
    }

    public function toQueries(string $tableName): Queries
    {
        return new Queries(sprintf('ALTER TABLE "%s" ADD %s', $tableName, $this->constraint->toSql()));
    }
}
