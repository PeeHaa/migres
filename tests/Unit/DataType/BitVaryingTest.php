<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\BitVarying;
use PHPUnit\Framework\TestCase;

class BitVaryingTest extends TestCase
{
    public function testToSqlWithoutLength(): void
    {
        $this->assertSame('bit varying', (new BitVarying())->toSql());
    }

    public function testToSqlWithLength(): void
    {
        $this->assertSame('bit varying(12)', (new BitVarying(12))->toSql());
    }
}
