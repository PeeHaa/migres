<?php declare(strict_types=1);

namespace PeeHaa\Migres\Specification;

use PeeHaa\Migres\Action\Action;
use PeeHaa\Migres\Action\AddColumnComment;
use PeeHaa\Migres\Action\AddTableComment;
use PeeHaa\Migres\Action\RemoveColumnComment;
use PeeHaa\Migres\Action\RemoveTableComment;
use PeeHaa\Migres\Constraint\NotNull;
use PeeHaa\Migres\DataType\Bit;
use PeeHaa\Migres\DataType\BitVarying;
use PeeHaa\Migres\Exception\InvalidDefaultValue;

final class ColumnOptions
{
    private Label $name;

    private bool $defaultValueSet = false;

    /** @var mixed */
    private $defaultValue;

    private bool $nullable = true;

    /** @var AddTableComment|RemoveTableComment|null */
    private ?Action $commentAction = null;

    public function __construct(Label $name)
    {
        $this->name = $name;
    }

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
        if (preg_match('~^B\'[01]+\'$~', $this->defaultValue)) {
            return $this->defaultValue;
        }

        if (gettype($this->defaultValue) === 'string' && preg_match('~^[01]+$~', $this->defaultValue)) {
            return sprintf("B'%s'", $this->defaultValue);
        }

        throw new InvalidDefaultValue($this->defaultValue);
    }

    public function notNull(): self
    {
        $this->nullable = false;

        return $this;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function hasOptions(): bool
    {
        return $this->nullable === false || $this->defaultValueSet;
    }

    public function comment(string $comment): self
    {
        $this->commentAction = new AddColumnComment($this->tableName, $this->name, $comment);

        return $this;
    }

    public function removeComment(): self
    {
        $this->commentAction = new RemoveColumnComment($this->tableName, $this->name);

        return $this;
    }

    public function toSql(Column $column): string
    {
        $sqlParts = [];

        if ($this->defaultValueSet) {
            $sqlParts[] = 'DEFAULT ' . $this->getDefaultValue($column);
        }

        if (!$this->nullable) {
            $sqlParts[] = (new NotNull())->toSql();
        }

        return implode(' ', $sqlParts);
    }

    /**
     * @internal
     * @return array<Action>
     */
    public function getActions(): array
    {
        $actions = [];

        if ($this->commentAction !== null) {
            $actions[] = $this->commentAction;
        }

        return $actions;
    }
}
