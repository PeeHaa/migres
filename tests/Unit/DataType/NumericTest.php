<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\Numeric;
use PHPUnit\Framework\TestCase;

class NumericTest extends TestCase
{
    public function testToSqlWithoutPrecisionOrScale(): void
    {
        $this->assertSame('numeric', (new Numeric())->toSql());
    }

    public function testToSqlWithoutPrecision(): void
    {
        $this->assertSame('numeric', (new Numeric(null, 2))->toSql());
    }

    public function testToSqlWithoutScale(): void
    {
        $this->assertSame('numeric(1)', (new Numeric(1))->toSql());
    }

    public function testToSqlWithPrecisionAndScale(): void
    {
        $this->assertSame('numeric(1,2)', (new Numeric(1, 2))->toSql());
    }
}
