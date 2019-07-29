<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\DoublePrecision;
use PHPUnit\Framework\TestCase;

class DoublePrecisionTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('double precision', (new DoublePrecision())->toSql());
    }
}
