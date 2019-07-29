<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\IntegerType;
use PHPUnit\Framework\TestCase;

class IntegerTypeTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('integer', (new IntegerType())->toSql());
    }
}
