<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\Point;
use PHPUnit\Framework\TestCase;

class PointTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('point', (new Point())->toSql());
    }
}
