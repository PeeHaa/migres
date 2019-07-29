<?php declare(strict_types=1);

namespace PeeHaa\Migres\Constraint;

interface Constraint
{
    public function toSql(): string;
}
