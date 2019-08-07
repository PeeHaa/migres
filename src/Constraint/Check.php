<?php declare(strict_types=1);

namespace PeeHaa\Migres\Constraint;

use PeeHaa\Migres\Specification\Label;

final class Check extends NamedConstraint implements Constraint
{
    private string $expression;

    public function __construct(Label $name, string $expression)
    {
        $this->expression = $expression;

        parent::__construct($name);
    }

    public function toSql(): string
    {
        return sprintf('CONSTRAINT "%s" CHECK (%s)', $this->name->toString(), $this->expression);
    }
}
