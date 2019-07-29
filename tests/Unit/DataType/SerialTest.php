<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\Serial;
use PHPUnit\Framework\TestCase;

class SerialTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('serial', (new Serial())->toSql());
    }
}
