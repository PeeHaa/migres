<?php declare(strict_types=1);

namespace PeeHaa\Migres;

use PeeHaa\Migres\Constraint\ColumnConstraint;
use PeeHaa\Migres\Constraint\NotNull;
use PeeHaa\Migres\DataType\Bit;
use PeeHaa\Migres\DataType\BitVarying;
use PeeHaa\Migres\DataType\Boolean;
use PeeHaa\Migres\DataType\Circle;
use PeeHaa\Migres\DataType\Line;
use PeeHaa\Migres\DataType\Money;
use PeeHaa\Migres\DataType\Path;
use PeeHaa\Migres\DataType\Point;
use PeeHaa\Migres\DataType\Polygon;
use PeeHaa\Migres\Specification\Column;
use PeeHaa\Migres\Exception\InvalidDefaultValue;

final class ColumnOptions
{
    private bool $defaultValueSet = false;

    /** @var mixed */
    private $defaultValue;

    /** @var array<ColumnConstraint> */
    private array $constraints = [];

    /**
     * @param mixed $default
     */
    public function setDefault($default): self
    {
        $this->defaultValueSet = true;

        $this->defaultValue = $default;

        return $this;
    }

    public function hasDefault(): bool
    {
        return $this->defaultValueSet;
    }

    public function getDefaultValue(Column $column): string
    {
        // handle values with casting, e.g.: '127.0.0.1'::inet
        if (gettype($this->defaultValue) === 'string' && preg_match('~::[^:]+$~', $this->defaultValue)) {
            return $this->defaultValue;
        }

        if ($column->getType() instanceof Bit || $column->getType() instanceof BitVarying) {
            return $this->getBinaryDefaultValue();
        }

        switch (gettype($this->defaultValue)) {
            case 'string':
                return sprintf("'%s'", $this->defaultValue);

            case 'boolean':
                return $this->defaultValue ? "'true'" : "'false'";

            case 'integer':
            case 'double':
                return (string) $this->defaultValue;

            case 'NULL':
                return 'NULL';

            default:
                throw new InvalidDefaultValue($this->defaultValue);
        }
    }

    private function getBinaryDefaultValue(): string
    {
        if (preg_match('~::"?bit(\(\d+\))?"?$~', $this->defaultValue)) {
            return $this->defaultValue;
        }

        if (preg_match('~^B\'[01]+\'$~', $this->defaultValue)) {
            return $this->defaultValue;
        }

        if (gettype($this->defaultValue) === 'string' && preg_match('~^[01]+$~', $this->defaultValue)) {
            return sprintf("B'%s'", $this->defaultValue);
        }

        throw new InvalidDefaultValue($this->defaultValue);
    }

    public function addConstraint(ColumnConstraint $constraint): self
    {
        $this->constraints[] = $constraint;

        return $this;
    }

    public function hasNotNullConstraints(): bool
    {
        foreach ($this->constraints as $constraint) {
            if (!$constraint instanceof NotNull) {
                continue;
            }

            return true;
        }

        return false;
    }

    /**
     * @internal
     * @throws InvalidDefaultValue
     */
    public function toSql(Column $column): string
    {
        $sqlParts = [];

        if ($this->defaultValueSet) {
            $sqlParts[] = 'DEFAULT ' . $this->getDefaultValue($column);
        }

        $constraints = [];

        /** @var ColumnConstraint $constraint */
        foreach ($this->constraints as $constraint) {
            $constraints[] = $constraint->toSql();
        }

        $sqlParts[] = implode(' ', $constraints);

        return implode(' ', $sqlParts);
    }
}
