<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\Uuid;
use PHPUnit\Framework\TestCase;

class UuidTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('uuid', (new Uuid())->toSql());
    }
}
