<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\Boolean;
use PHPUnit\Framework\TestCase;

class BooleanTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('boolean', (new Boolean())->toSql());
    }
}
