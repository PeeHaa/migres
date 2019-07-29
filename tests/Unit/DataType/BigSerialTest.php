<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\BigSerial;
use PHPUnit\Framework\TestCase;

class BigSerialTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('bigserial', (new BigSerial())->toSql());
    }
}
