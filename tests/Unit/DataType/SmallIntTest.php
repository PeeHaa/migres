<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\SmallInt;
use PHPUnit\Framework\TestCase;

class SmallIntTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('smallint', (new SmallInt())->toSql());
    }
}
