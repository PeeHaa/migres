<?php declare(strict_types=1);

namespace PeeHaa\Migres\Constraint;

final class PrimaryKey implements ColumnConstraint
{
    /**
     * @internal
     */
    public function toSql(): string
    {
        return 'PRIMARY KEY';
    }
}
