<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\Cidr;
use PHPUnit\Framework\TestCase;

class CidrTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('cidr', (new Cidr())->toSql());
    }
}
