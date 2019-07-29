<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\TimestampWithTimezone;
use PHPUnit\Framework\TestCase;

class TimestampWithTimezoneTest extends TestCase
{
    public function testToSqlWithoutPrecision(): void
    {
        $this->assertSame('timestamp with time zone', (new TimestampWithTimezone())->toSql());
    }

    public function testToSqlWithPrecision(): void
    {
        $this->assertSame('timestamp(12) with time zone', (new TimestampWithTimezone(12))->toSql());
    }
}
