<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\ByteA;
use PHPUnit\Framework\TestCase;

class ByteATest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('bytea', (new ByteA())->toSql());
    }
}
