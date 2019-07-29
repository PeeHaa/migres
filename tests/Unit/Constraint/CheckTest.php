<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Constraint;

use PeeHaa\Migres\Constraint\Check;
use PHPUnit\Framework\TestCase;

class CheckTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame(
            'CONSTRAINT "check_name" CHECK (1 = 1)',
            (new Check('check_name', '1 = 1'))->toSql(),
        );
    }
}
