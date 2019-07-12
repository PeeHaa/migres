<?php declare(strict_types=1);

namespace PeeHaa\Migres\Constraint;

interface Constraint
{
    /**
     * @internal
     */
    public function toSql(): string;
}
