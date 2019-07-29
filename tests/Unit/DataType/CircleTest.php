<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\Circle;
use PHPUnit\Framework\TestCase;

class CircleTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('circle', (new Circle())->toSql());
    }
}
