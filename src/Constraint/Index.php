<?php declare(strict_types=1);

namespace PeeHaa\Migres\Constraint;

use PeeHaa\Migres\Specification\Label;

final class Index extends NamedConstraint implements Constraint
{
    private Label $tableName;

    /** @var array<Label> */
    private array $columns;

    private ?string $method;

    /**
     * @param array<string> $columns
     */
    public function __construct(Label $name, Label $tableName, array $columns, ?string $method = null)
    {
        $this->tableName = $tableName;
        $this->columns   = $columns;
        $this->method    = $method;

        parent::__construct($name);
    }

    public function toSql(): string
    {
        $sql = sprintf('CREATE INDEX "%s" ON "%s"', $this->name->toString(), $this->tableName->toString());

        if ($this->method !== null) {
            $sql .= ' USING ' . $this->method;
        }

        $sql .= sprintf(' (%s)', implode(', ', array_map(fn (Label $column) => $column->toString(), $this->columns)));

        return $sql;
    }
}
