<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\Date;
use PHPUnit\Framework\TestCase;

class DateTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('date', (new Date())->toSql());
    }
}
