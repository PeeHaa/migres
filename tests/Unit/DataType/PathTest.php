<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\Path;
use PHPUnit\Framework\TestCase;

class PathTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('path', (new Path())->toSql());
    }
}
