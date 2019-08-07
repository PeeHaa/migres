<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Constraint\Unique;
use PeeHaa\Migres\Migration\Queries;
use PeeHaa\Migres\Specification\Label;

final class AddUniqueConstraint extends TableAction implements Action
{
    private Unique $constraint;

    public function __construct(Label $tableName, Unique $constraint)
    {
        parent::__construct($tableName);

        $this->constraint = $constraint;
    }

    public function getConstraint(): Unique
    {
        return $this->constraint;
    }

    public function toQueries(): Queries
    {
        return new Queries(sprintf('ALTER TABLE "%s" ADD %s', $this->tableName->toString(), $this->constraint->toSql()));
    }
}
