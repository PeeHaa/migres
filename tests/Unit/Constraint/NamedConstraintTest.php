<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Constraint;

use PeeHaa\Migres\Constraint\NamedConstraint;
use PHPUnit\Framework\TestCase;

class NamedConstraintTest extends TestCase
{
    public function testGetName(): void
    {
        $namedConstraint = new class('ConstraintName') extends NamedConstraint {
        };

        $this->assertSame('ConstraintName', $namedConstraint->getName());
    }
}
