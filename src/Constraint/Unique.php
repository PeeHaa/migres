<?php declare(strict_types=1);

namespace PeeHaa\Migres\Constraint;

use PeeHaa\Migres\Specification\Label;

final class Unique extends NamedConstraint implements Constraint
{
    /** @var array<Label> */
    private array $columns;

    public function __construct(Label $name, Label ...$columns)
    {
        $this->columns = $columns;

        parent::__construct($name);
    }

    public function toSql(): string
    {
        $columns = array_reduce($this->columns, static function (array $columns, Label $name): array {
            $columns[] = sprintf('"%s"', $name->toString());

            return $columns;
        }, []);

        return sprintf('CONSTRAINT "%s" UNIQUE (%s)', $this->name->toString(), implode(', ', $columns));
    }
}
