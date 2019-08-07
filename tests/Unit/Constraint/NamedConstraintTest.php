<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Constraint;

use PeeHaa\Migres\Constraint\NamedConstraint;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class NamedConstraintTest extends TestCase
{
    public function testGetName(): void
    {
        $namedConstraint = new class(new Label('ConstraintName')) extends NamedConstraint {
        };

        $this->assertSame('ConstraintName', $namedConstraint->getName()->toString());
    }
}
