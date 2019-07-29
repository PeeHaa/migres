<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Constraint;

use PeeHaa\Migres\Constraint\NotNull;
use PHPUnit\Framework\TestCase;

class NotNullTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('NOT NULL', (new NotNull())->toSql());
    }
}
