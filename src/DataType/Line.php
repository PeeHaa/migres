<?php declare(strict_types=1);

namespace PeeHaa\Migres\DataType;

final class Line implements Type
{
    /**
     * @internal
     */
    public function toSql(): string
    {
        return 'line';
    }
}
