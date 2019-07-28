<?php declare(strict_types=1);

namespace PeeHaa\Migres\Constraint;

final class Check extends NamedConstraint implements Constraint
{
    private string $expression;

    public function __construct(string $name, string $expression)
    {
        $this->expression = $expression;

        parent::__construct($name);
    }

    /**
     * @internal
     */
    public function toSql(): string
    {
        return sprintf('CONSTRAINT "%s" CHECK (%s)', $this->name, $this->expression);
    }
}
