<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\FloatType;
use PHPUnit\Framework\TestCase;

class FloatTypeTest extends TestCase
{
    public function testToSqlWithoutPrecision(): void
    {
        $this->assertSame('float', (new FloatType())->toSql());
    }

    public function testToSqlWithPrecision(): void
    {
        $this->assertSame('float(12)', (new FloatType(12))->toSql());
    }
}
