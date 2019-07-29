<?php declare(strict_types=1);

namespace PeeHaa\Migres\DataType;

final class Serial implements Type
{
    public function toSql(): string
    {
        return 'serial';
    }
}
