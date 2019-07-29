<?php declare(strict_types=1);

namespace PeeHaa\Migres\DataType;

interface Type
{
    public function toSql(): string;
}
