<?php declare(strict_types=1);

namespace PeeHaa\Migres\Constraint;

final class Unique extends NamedConstraint implements ColumnConstraint
{
    /**
     * @internal
     */
    public function toSql(): string
    {
        return sprintf('CONSTRAINT "%s" UNIQUE', $this->name);
    }
}
