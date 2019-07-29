<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\TimestampWithoutTimezone;
use PHPUnit\Framework\TestCase;

class TimestampWithoutTimezoneTest extends TestCase
{
    public function testToSqlWithoutPrecision(): void
    {
        $this->assertSame('timestamp without time zone', (new TimestampWithoutTimezone())->toSql());
    }

    public function testToSqlWithPrecision(): void
    {
        $this->assertSame('timestamp(12) without time zone', (new TimestampWithoutTimezone(12))->toSql());
    }
}
