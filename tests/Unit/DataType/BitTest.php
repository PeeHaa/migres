<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\Bit;
use PHPUnit\Framework\TestCase;

class BitTest extends TestCase
{
    public function testToSqlWithoutLength(): void
    {
        $this->assertSame('bit', (new Bit())->toSql());
    }

    public function testToSqlWithLength(): void
    {
        $this->assertSame('bit(12)', (new Bit(12))->toSql());
    }
}
