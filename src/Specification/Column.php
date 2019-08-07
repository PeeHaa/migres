<?php declare(strict_types=1);

namespace PeeHaa\Migres\Specification;

use PeeHaa\Migres\DataType\Type;

final class Column
{
    private Label $name;

    private Type $type;

    private ColumnOptions $options;

    public function __construct(Label $name, Type $type)
    {
        $this->name    = $name;
        $this->type    = $type;
        $this->options = new ColumnOptions();
    }

    public function getName(): Label
    {
        return $this->name;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function notNull(): self
    {
        $this->options->notNull();

        return $this;
    }

    /**
     * @param mixed $defaultValue
     */
    public function default($defaultValue): self
    {
        $this->options->setDefault($defaultValue);

        return $this;
    }

    public function getOptions(): ColumnOptions
    {
        return $this->options;
    }

    public function toSql(): string
    {
        $sql = sprintf('"%s" %s', $this->name->toString(), $this->type->toSql());

        if (!$this->options->hasOptions()) {
            return $sql;
        }

        return $sql . ' ' . $this->options->toSql($this);
    }
}
