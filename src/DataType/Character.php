<?php declare(strict_types=1);

namespace PeeHaa\Migres\DataType;

final class Character implements Type
{
    private ?int $length;

    public function __construct(?int $length = null)
    {
        $this->length = $length;
    }

    public function toSql(): string
    {
        if ($this->length === null) {
            return 'character';
        }

        return sprintf('character(%d)', $this->length);
    }
}
