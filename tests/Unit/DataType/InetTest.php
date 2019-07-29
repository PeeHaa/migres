<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\Inet;
use PHPUnit\Framework\TestCase;

class InetTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('inet', (new Inet())->toSql());
    }
}
