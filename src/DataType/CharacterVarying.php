<?php declare(strict_types=1);

namespace PeeHaa\Migres\DataType;

final class CharacterVarying implements Type
{
    private ?int $length;

    public function __construct(?int $length = null)
    {
        $this->length = $length;
    }

    public function toSql(): string
    {
        if ($this->length === null) {
            return 'character varying';
        }

        return sprintf('character varying(%d)', $this->length);
    }
}
