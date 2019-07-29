<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\TimeWithoutTimezone;
use PHPUnit\Framework\TestCase;

class TimeWithoutTimezoneTest extends TestCase
{
    public function testToSqlWithoutPrecision(): void
    {
        $this->assertSame('time without time zone', (new TimeWithoutTimezone())->toSql());
    }

    public function testToSqlWithPrecision(): void
    {
        $this->assertSame('time(12) without time zone', (new TimeWithoutTimezone(12))->toSql());
    }
}
