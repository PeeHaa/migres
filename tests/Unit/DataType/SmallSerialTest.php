<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\SmallSerial;
use PHPUnit\Framework\TestCase;

class SmallSerialTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('smallserial', (new SmallSerial())->toSql());
    }
}
