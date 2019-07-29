<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\Lseg;
use PHPUnit\Framework\TestCase;

class LsegTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('lseg', (new Lseg())->toSql());
    }
}
