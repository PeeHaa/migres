<?php declare(strict_types=1);

namespace PeeHaa\Migres;

use PeeHaa\Migres\DataType\Type;

final class Column
{
    private string $name;

    private Type $type;

    private ColumnOptions $options;

    public function __construct(string $name, Type $type, ?ColumnOptions $options = null)
    {
        $this->name    = $name;
        $this->type    = $type;
        $this->options = $options ?? new ColumnOptions();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function getOptions(): ColumnOptions
    {
        return $this->options;
    }

    public function toSql(): string
    {
        $sql = sprintf('"%s" %s', $this->name, $this->type->toSql());

        if (!$this->options) {
            return $sql;
        }

        return $sql . ' ' . $this->options->toSql($this);
    }
}
