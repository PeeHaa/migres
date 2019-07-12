<?php declare(strict_types=1);

namespace PeeHaa\Migres\DataType;

use PeeHaa\Migres\Exception\InvalidDataTypeSpecification;

final class Character implements Type
{
    private const SPEC_PATTERN = '~(?:character|char)\s*\(\s*(?P<length>\d+)?\s*\)~i';

    private ?int $length;

    public function __construct(?int $length = null)
    {
        $this->length = $length;
    }

    public static function fromString(string $specification): self
    {
        if (!preg_match(self::SPEC_PATTERN, $specification, $matches)) {
            throw new InvalidDataTypeSpecification($specification, self::class);
        }

        return new self(isset($matches['length']) ? (int) $matches['length'] : null);
    }

    /**
     * @internal
     */
    public function toSql(): string
    {
        if ($this->length === null) {
            return 'character';
        }

        return sprintf('character(%d)', $this->length);
    }
}
