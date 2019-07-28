<?php declare(strict_types=1);

namespace PeeHaa\Migres\DataType;

final class TimestampWithoutTimezone implements Type
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
            return 'timestamp without time zone';
        }

        return sprintf('timestamp(%d) without time zone', $this->precision);
    }
}
