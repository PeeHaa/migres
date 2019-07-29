<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\MacAddr;
use PHPUnit\Framework\TestCase;

class MacAddrTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('macaddr', (new MacAddr())->toSql());
    }
}
