<?php declare(strict_types=1);

namespace PeeHaa\Migres\DataType;

interface Type
{
    /**
     * @internal
     */
    public function toSql(): string;
}