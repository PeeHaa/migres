<?php declare(strict_types=1);

namespace PeeHaa\Migres\DataType;

final class JsonB implements Type
{
    public function toSql(): string
    {
        return 'jsonb';
    }
}
