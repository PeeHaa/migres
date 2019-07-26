<?php declare(strict_types=1);

namespace PeeHaa\Migres\DataType;

final class Path implements Type
{
    /**
     * @internal
     */
    public function toSql(): string
    {
        return 'path';
    }
}