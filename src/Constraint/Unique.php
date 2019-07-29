<?php declare(strict_types=1);

namespace PeeHaa\Migres\Constraint;

final class Unique extends NamedConstraint implements Constraint
{
    /** @var array<string> */
    private array $columns;

    public function __construct(string $name, string ...$columns)
    {
        $this->columns = $columns;

        parent::__construct($name);
    }

    public function toSql(): string
    {
        $columns = array_reduce($this->columns, static function (array $columns, string $name): array {
            $columns[] = sprintf('"%s"', $name);

            return $columns;
        }, []);

        return sprintf('CONSTRAINT "%s" UNIQUE (%s)', $this->name, implode(', ', $columns));
    }
}
