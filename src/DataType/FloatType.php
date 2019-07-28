<?php declare(strict_types=1);

namespace PeeHaa\Migres\DataType;

final class FloatType implements Type
{
    private ?int $precision;

    public function __construct(?int $precision = null)
    {
        $this->precision = $precision;
    }

    /**
     * @internal
     */
    public function toSql(): string
    {
        if ($this->precision === null) {
            return 'float';
        }

        return sprintf('float(%d)', $this->precision);
    }
}
