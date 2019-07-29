<?php declare(strict_types=1);

namespace PeeHaa\Migres\DataType;

final class BitVarying implements Type
{
    private ?int $length;

    public function __construct(?int $length = null)
    {
        $this->length = $length;
    }

    public function toSql(): string
    {
        if ($this->length === null) {
            return 'bit varying';
        }

        return sprintf('bit varying(%d)', $this->length);
    }
}
