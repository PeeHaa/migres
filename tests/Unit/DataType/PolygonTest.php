<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\Polygon;
use PHPUnit\Framework\TestCase;

class PolygonTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('polygon', (new Polygon())->toSql());
    }
}
