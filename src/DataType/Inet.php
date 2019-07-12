<?php declare(strict_types=1);

namespace PeeHaa\Migres\DataType;

final class Inet implements Type
{
    /**
     * @internal
     */
    public function toSql(): string
    {
        return 'inet';
    }
}
