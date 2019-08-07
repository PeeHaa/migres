<?php declare(strict_types=1);

namespace PeeHaa\Migres\Constraint;

use PeeHaa\Migres\Specification\Label;

abstract class NamedConstraint
{
    protected Label $name;

    public function __construct(Label $name)
    {
        $this->name = $name;
    }

    public function getName(): Label
    {
        return $this->name;
    }
}
