<?php declare(strict_types=1);

namespace PeeHaa\Migres\Constraint;

final class Index extends NamedConstraint implements Constraint
{
    private string $tableName;

    /** @var array<string> */
    private array $columns;

    private ?string $method;

    /**
     * @param array<string> $columns
     */
    public function __construct(string $name, string $tableName, array $columns, ?string $method = null)
    {
        $this->tableName = $tableName;
        $this->columns   = $columns;
        $this->method    = $method;

        parent::__construct($name);
    }

    public function toSql(): string
    {
        $sql = sprintf('CREATE INDEX "%s" ON "%s"', $this->name, $this->tableName);

        if ($this->method !== null) {
            $sql .= ' USING ' . $this->method;
        }

        $sql .= sprintf(' (%s)', implode(', ', $this->columns));

        return $sql;
    }
}
