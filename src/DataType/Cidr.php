<?php declare(strict_types=1);

namespace PeeHaa\Migres\DataType;

final class Cidr implements Type
{
    /**
     * @internal
     */
    public function toSql(): string
    {
        return 'cidr';
    }
}
