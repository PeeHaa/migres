<?php declare(strict_types=1);

namespace PeeHaa\Migres;

use PeeHaa\Migres\Constraint\ColumnConstraint;
use PeeHaa\Migres\Constraint\NotNull;
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

    public function getDefaultValue(): string
    {
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

    public function addConstraints(ColumnConstraint ...$constraints): self
    {
        foreach ($constraints as $constraint) {
            $this->addConstraint($constraint);
        }

        return $this;
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
    public function toSql(): string
    {
        $sqlParts = [];

        if ($this->defaultValueSet) {
            $sqlParts[] = 'DEFAULT ' . $this->getDefaultValue();
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
