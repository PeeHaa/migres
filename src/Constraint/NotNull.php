<?php declare(strict_types=1);

namespace PeeHaa\Migres\Constraint;

final class NotNull implements Constraint
{
    /**
     * @internal
     */
    public function toSql(): string
    {
        return 'NOT NULL';
    }
}
