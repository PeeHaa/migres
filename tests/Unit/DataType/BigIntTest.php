<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\BigInt;
use PHPUnit\Framework\TestCase;

class BigIntTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('bigint', (new BigInt())->toSql());
    }
}
