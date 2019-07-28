<?php declare(strict_types=1);

namespace PeeHaa\Migres\DataType;

final class Bit implements Type
{
    private ?int $length;

    public function __construct(?int $length = null)
    {
        $this->length = $length;
    }

    /**
     * @internal
     */
    public function toSql(): string
    {
        if ($this->length === null) {
            return 'bit';
        }

        return sprintf('bit(%d)', $this->length);
    }
}
