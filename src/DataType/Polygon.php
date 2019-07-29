<?php declare(strict_types=1);

namespace PeeHaa\Migres\DataType;

final class Polygon implements Type
{
    public function toSql(): string
    {
        return 'polygon';
    }
}
