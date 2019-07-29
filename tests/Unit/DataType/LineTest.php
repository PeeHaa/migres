<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\Line;
use PHPUnit\Framework\TestCase;

class LineTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('line', (new Line())->toSql());
    }
}
