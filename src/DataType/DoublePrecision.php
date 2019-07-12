<?php declare(strict_types=1);

namespace PeeHaa\Migres\DataType;

final class DoublePrecision implements Type
{
    /**
     * @internal
     */
    public function toSql(): string
    {
        return 'double precision';
    }
}
