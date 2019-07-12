<?php declare(strict_types=1);

namespace PeeHaa\Migres\Constraint;

abstract class NamedConstraint
{
    protected string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
