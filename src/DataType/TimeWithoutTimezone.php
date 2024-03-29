<?php declare(strict_types=1);

namespace PeeHaa\Migres\DataType;

final class TimeWithoutTimezone implements Type
{
    private ?int $precision;

    public function __construct(?int $precision = null)
    {
        $this->precision = $precision;
    }

    public function toSql(): string
    {
        if ($this->precision === null) {
            return 'time without time zone';
        }

        return sprintf('time(%d) without time zone', $this->precision);
    }
}
