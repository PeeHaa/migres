<?php declare(strict_types=1);

namespace PeeHaa\Migres\DataType;

final class TimestampWithTimezone implements Type
{
    private ?int $precision;

    public function __construct(?int $precision = null)
    {
        $this->precision = $precision;
    }

    public function toSql(): string
    {
        if ($this->precision === null) {
            return 'timestamp with time zone';
        }

        return sprintf('timestamp(%d) with time zone', $this->precision);
    }
}
