<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\TimeWithTimezone;
use PHPUnit\Framework\TestCase;

class TimeWithTimezoneTest extends TestCase
{
    public function testToSqlWithoutPrecision(): void
    {
        $this->assertSame('time with time zone', (new TimeWithTimezone())->toSql());
    }

    public function testToSqlWithPrecision(): void
    {
        $this->assertSame('time(12) with time zone', (new TimeWithTimezone(12))->toSql());
    }
}
