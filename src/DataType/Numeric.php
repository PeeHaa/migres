<?php declare(strict_types=1);

namespace PeeHaa\Migres\DataType;

final class Numeric implements Type
{
    private ?int $precision;

    private ?int $scale;

    public function __construct(?int $precision = null, ?int $scale = null)
    {
        $this->precision = $precision;
        $this->scale     = $scale;
    }

    /**
     * @internal
     */
    public function toSql(): string
    {
        if ($this->precision === null) {
            return 'numeric';
        }

        if ($this->scale === null) {
            return sprintf('numeric(%d)', $this->precision);
        }

        return sprintf('numeric(%d,%d)', $this->precision, $this->scale);
    }
}
