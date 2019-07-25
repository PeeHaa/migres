<?php declare(strict_types=1);

namespace PeeHaa\Migres\DataType;

use PeeHaa\Migres\Exception\InvalidDataTypeSpecification;

final class TimeWithTimezone implements Type
{
    private const SPEC_PATTERN = '~time\s*(?:\(\s*(?P<precision>\d+)\s*\))?\s*with time zone~i';

    private ?int $precision;

    public function __construct(?int $precision = null)
    {
        $this->precision = $precision;
    }

    public static function fromString(string $specification): self
    {
        if (!preg_match(self::SPEC_PATTERN, $specification, $matches)) {
            throw new InvalidDataTypeSpecification($specification, self::class);
        }

        return new self(isset($matches['precision']) ? (int) $matches['precision'] : null);
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
