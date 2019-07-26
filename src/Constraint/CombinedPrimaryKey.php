<?php declare(strict_types=1);

namespace PeeHaa\Migres\Constraint;

final class CombinedPrimaryKey extends NamedConstraint implements TableConstraint
{
    /** @var array<string> */
    private array $columns;

    /**
     * @param array<string> $fields
     */
    public function __construct(string $name, string ...$columns)
    {
        $this->columns = $columns;

        parent::__construct($name);
    }

    /**
     * @internal
     */
    public function toSql(): string
    {
        $columns = array_reduce($this->columns, static function (array $columns, string $name): array {
            $columns[] = sprintf('"%s"', $name);

            return $columns;
        }, []);

        return sprintf('CONSTRAINT "%s" PRIMARY KEY (%s)', $this->name, implode(', ', $columns));
    }
}