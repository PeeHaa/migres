<?php declare(strict_types=1);

namespace PeeHaa\Migres\DataType;

final class Date implements Type
{
    /**
     * @internal
     */
    public function toSql(): string
    {
        return 'date';
    }
}
