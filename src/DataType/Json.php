<?php declare(strict_types=1);

namespace PeeHaa\Migres\DataType;

final class Json implements Type
{
    /**
     * @internal
     */
    public function toSql(): string
    {
        return 'json';
    }
}
