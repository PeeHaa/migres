<?php declare(strict_types=1);

namespace PeeHaa\Migres\DataType;

final class TimeWithTimezone implements Type
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
            return 'time with time zone';
        }

        return sprintf('time(%d) with time zone', $this->precision);
    }
}
