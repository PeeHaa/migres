<?php declare(strict_types=1);

namespace PeeHaa\Migres\DataType;

use PeeHaa\Migres\Exception\InvalidDataTypeSpecification;

final class Numeric implements Type
{
    private const SPEC_PATTERN = '~(?:numeric|decimal)\s*\(\s*(?P<precision>\d)+\s*(?:,\s*(?P<scale>\d+))?\s*\)~i';

    private int $precision;

    private int $scale;

    public function __construct(int $precision, int $scale = 0)
    {
        $this->precision = $precision;
        $this->scale     = $scale;
    }

    public static function fromString(string $specification): self
    {
        if (!preg_match(self::SPEC_PATTERN, $specification, $matches)) {
            throw new InvalidDataTypeSpecification($specification, self::class);
        }

        return new self(isset($matches['precision']) ? (int) $matches['scale'] : 0);
    }

    /**
     * @internal
     */
    public function toSql(): string
    {
        return sprintf('numeric(%d,%d)', $this->precision, $this->scale);
    }
}
