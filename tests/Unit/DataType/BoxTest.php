<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\Box;
use PHPUnit\Framework\TestCase;

class BoxTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('box', (new Box())->toSql());
    }
}
