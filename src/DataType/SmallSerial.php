<?php declare(strict_types=1);

namespace PeeHaa\Migres\DataType;

final class SmallSerial implements Type
{
    public function toSql(): string
    {
        return 'smallserial';
    }
}
