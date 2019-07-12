<?php declare(strict_types=1);

namespace PeeHaa\Migres\DataType;

final class ByteA implements Type
{
    /**
     * @internal
     */
    public function toSql(): string
    {
        return 'bytea';
    }
}
